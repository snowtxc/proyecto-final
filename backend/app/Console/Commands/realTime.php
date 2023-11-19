<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Helpers\FileHelper;
use SimpleXMLElement;
use Illuminate\Support\Facades\Storage;
use App\Models\Nodo;
use App\Models\Componente;
use App\Models\Registro;
use App\Models\Alarma;
use App\Models\AlarmaUser;
use Illuminate\Support\Facades\DB;

use App\Events\appendRegistrosDevice;
use App\Events\PushAlarmaNotificacion;
use App\Events\UpdateProcessesActivity;

use App\Utils\Helper;


use Illuminate\Support\Facades\Mail;



class realTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:real-time-xml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */



    private function generarXml($data){
        $xml = new SimpleXMLElement('<xml></xml>');
        $device = $xml->addChild('device');

        $device->addChild('id', $data['deviceId']);
        $device->addChild('name', $data['deviceName']);
        $device->addChild('mark', '');
        $device->addChild('model', '');
        $device->addChild('description',$data['deviceDescription']);
        $device->addChild('priv_address', $data['deviceIp']);
        $device->addChild('pub_address', $data['deviceIp']);
        $device->addChild('post_time', 5);
        $device->addChild('post_unit', 'm');


        $marcas =  $data['marcas'];
        foreach($marcas as $marca){
            $data1 = $device->addChild('data');
            $data1->addAttribute('id', $marca['unidadId']);
            $data1->addChild('dataname', $marca['unidadNombre']);
            $data1->addChild('datavalue', $marca['marca']);
            $data1->addChild('dataunit', $marca['unidad']);
            $data1->addChild('datatime', $marca['fecha']);
        }
        return $xml->asXml();
    }

    private function generarRegistros(){
        $nodos = Nodo::all();
        foreach($nodos as $nodo){
            $componente = $nodo->componente;
            $componenteOn = $componente->On ==  1 ? true: false;
            if($componenteOn){
                $unidades =  $componente->unidades;
                $marcas  = array();
                foreach($unidades as $unidad){
                    $min =  $unidad->pivot->min;
                    $max = $unidad->pivot->max;
                    $fecha = $unidad->pivot->created_at;
                    array_push($marcas , [
                        "unidadId" => $unidad->id,
                        "unidadNombre" => $unidad->nombre,
                        "marca" => rand($min,$max),
                        "unidad" => $unidad->unidad,
                        "fecha" => $unidad->fecha
                    ]);
                }
                $data = [
                    "deviceId" => $componente->id,
                    "deviceName" => $componente->Nombre,
                    "deviceDescription" => $componente->Descripcion,
                    "deviceIp" => $componente->DireccionIp,
                    "marcas" => $marcas
                ];
                $fileName = "device-".$componente->id."-".time().".xml";
                $xml = $this->generarXml($data);
                $filePath = 'xmls/'.$fileName;
                Storage::put($filePath, $xml);

                }

        }
    }

    private  function obtenerRegistros(){
         $files  = Storage::files("xmls");
         $xmlsFiles = array();
         foreach($files as $file){
            $xmlString = Storage::get($file);
            $xml = simplexml_load_string($xmlString);
            array_push($xmlsFiles,$xml);
            Storage::delete($file);
         }

         $alarmas = array();
         foreach($xmlsFiles as $deviceRow){
            $deviceId = $deviceRow->device->id;

            $componente = Componente::find($deviceId);
            $componenteUnidades = $componente->unidades;
            $etapa =  $componente->nodo->etapa;

            $registrosCreateds = array();
            foreach ($deviceRow->device->data as $data) {
                $dataId = (int) $data['id'];
                $dataValue = (float) $data->datavalue;
                $dataUnit = (string) $data->dataunit;
                $dataTime = (string) $data->datatime;

                $newRegistroBody = [
                    "Marca" => $dataValue,
                    "etapa_id"  => $etapa->id,
                    "unidad_id" => $dataId,
                    "componente_id" => $componente->id,
                ];

                $newRegistro = $componente->registros()->create($newRegistroBody);
                $newRegistro->unidad;
                $newRegistro->etapa;
                $newRegistro->etapa->proceso;
                array_push($registrosCreateds, $newRegistro);


                $index  = array_search($dataId, array_column(json_decode(json_encode($componenteUnidades), true), 'id'));
                $unidadFind = $componenteUnidades[$index];

                $minValue  =  $unidadFind->pivot->min;
                $maxValue  =  $unidadFind->pivot->max;


                if($dataValue < $minValue ||  $dataValue > $maxValue){
                    $motivo = '';
                    if($dataValue < $componente->min){
                        $motivo =
                        "El Dispositivo $componente->Nombre ha marcado " . $dataValue . " " .
                         $newRegistro->unidad->unidad . "(" . $newRegistro->unidad->nombre . ") por debajo del rango de " . $minValue . " " .
                          $newRegistro->unidad->unidad . " a " . $maxValue . " " .  $newRegistro->unidad->unidad;
                    }else{
                        $motivo = "El Dispositivo $componente->Nombre ha marcado " . $dataValue . " " .
                         $newRegistro->unidad->unidad . "(" . $newRegistro->unidad->nombre . ") por encima del rango de " . $minValue . " " .
                          $newRegistro->unidad->unidad . " a " . $maxValue . " " . $newRegistro->unidad->unidad;
                    }
                    array_push($alarmas, [
                        "componente" =>$componente,
                        "motivo" => $motivo
                    ]);
                }
            }
            broadcast(new appendRegistrosDevice($componente->id, $registrosCreateds));



         }

        foreach($alarmas as $alarma){

            $this->generarAlarma($alarma["componente"], $alarma["motivo"]);
        }

    }

    private function generarAlarma($componente,$motivo){
        $tipoComponente = $componente->tipoComponente;

        $proceso = $componente->nodo->etapa->proceso;

        $alarma = new Alarma;
        $alarma->componente_id = $componente->id;
        $alarma->proceso_id = $proceso->id;
        $alarma->Motivo =  $motivo;
        $alarma->save();
        $usuarios = $proceso->users;
        foreach ($usuarios as $usuario) {
            $data = [
                'name' => $usuario->name,
                'dispositivo' => $componente->Nombre,
                'proceso' => $proceso->Nombre,

            ];
            $componenteInfo  =[
                "tipoComponenteImage" => FileHelper::getRealPath($tipoComponente->Imagen),
                "tipoComponenteNombre" => $tipoComponente->Nombre,
                "Nombre" => $componente->Nombre,
                "Descripcion" => $componente->Descripcion,
                "Unidad" => $componente->Unidad,
                "DireccionIp" => $componente->DireccionIp,
                "tipo_componente_id" => $componente->tipo_componente_id,
                "id" => $componente->id,
            ];

            $proceso = $alarma->proceso;
            $dataAlarm = [
                "id" => $alarma->id,
                "componente" => $componenteInfo,
                "motivo"  => $alarma->Motivo,
                "created_at" => $alarma->created_at,
                "proceso"  => $proceso
            ];
            broadcast(new PushAlarmaNotificacion($usuario->id, $dataAlarm));
            Mail::send('emails.alarma', $data, function ($message) use ($usuario) {
                $message->to($usuario->email)->subject('Nueva Alarma');
            });

            $alarmaUser = new AlarmaUser;
            $alarmaUser->alarma_id = $alarma->id;
            $alarmaUser->user_id = $usuario->id;
            $alarmaUser->leida = false;
            $alarmaUser->save();
        }

    }

    private function getActividadPorProcesos(){
        $data = Helper::processesActivityLastHour();
        $arrayData = json_decode($data, true);
        broadcast(new UpdateProcessesActivity($arrayData));
    }



    public function handle()
    {
        while(true){
            $this->generarRegistros();
            $this->obtenerRegistros();
            $this->getActividadPorProcesos();
            sleep(3);
        }
    }
}
