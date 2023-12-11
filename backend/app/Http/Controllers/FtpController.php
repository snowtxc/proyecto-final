<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Componente;
use App\Models\Registro;
use App\Models\Nodo;
use App\Models\Alarma;
use App\Models\AlarmaUser;
use SimpleXMLElement;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileHelper;



use App\Events\appendRegistrosDevice;
use App\Events\PushAlarmaNotificacion;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Mail;

use App\Events\UpdateProcessesActivity;
use App\Utils\Helper;


class FtpController extends Controller
{





    public function downloadFileFromFTP()
    {

        $ftpServer = env('FTP_HOST');
        $ftpUsername = env('FTP_USERNAME');
        $ftpPassword = env('FTP_PASSWORD');
        $remoteDirectory = '/data-x/x-input';
        $localXmlPath = public_path('device.xml');

        $ftpConnection = ftp_connect($ftpServer);
        if (!$ftpConnection) {
           $this->error('No se pudo establece la conexion con el servidor stp');

        }

        if (!ftp_login($ftpConnection, $ftpUsername, $ftpPassword)) {
            $this->error('Las credenciales de la conexion stp son invalidas');
        }


        $remoteFiles = ftp_nlist($ftpConnection, $remoteDirectory);

        $result = array();

        foreach($remoteFiles as $remoteFilePath){
            if (substr($remoteFilePath, -4) === ".xml") {
                $memoryStream = fopen('php://temp', 'r+');
                $remoteXmlContent = ftp_fget($ftpConnection, $memoryStream, $remoteFilePath, FTP_BINARY);
                rewind($memoryStream);
                $remoteXmlContent = stream_get_contents($memoryStream);

                array_push($result, simplexml_load_string($remoteXmlContent));
                //ftp_delete($ftpConnection, $remoteFilePath);
                ftruncate($memoryStream, 0);
                ftp_close($ftpConnection);

            }

        }

        $alarmas = array();
        foreach($result as $deviceRow){
            return response()->json(["id" => $deviceRow->device->id]);
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
                         $newRegistro->unidad->unidad . "(" . $newRegistro->unidad->nombre . ") por debajo del rango de " . $componente->min . " " .
                          $newRegistro->unidad->unidad . " a " . $componente->max . " " . $newRegistro->max;
                    }else{
                        $motivo = "El Dispositivo $componente->Nombre ha marcado " . $dataValue . " " .
                         $newRegistro->unidad->unidad . "(" . $newRegistro->unidad->nombre . ") por debajo del rango de " . $componente->min . " " .
                          $newRegistro->unidad->unidad . " a " . $componente->max . " " . $newRegistro->max;
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
            $dataMail = [
                'name' => $usuario->name,
                'dispositivo' => $componente->Nombre,
                'proceso' => $proceso->Nombre,
                'motivo' => $motivo
            ];

            Mail::send('emails.alarma', $dataMail, function ($message) use ($usuario) {
                $message->to($usuario->email)->subject('Nueva Alarma');
            });
            $alarmaUser = new AlarmaUser;
            $alarmaUser->alarma_id = $alarma->id;
            $alarmaUser->user_id = $usuario->id;
            $alarmaUser->leida = false;
            $alarmaUser->save();

            $componenteInfo  =[
                "tipoComponenteImage" => $pathImage,
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

        }

    }



    public function last24Hour(){

        $nodos = Nodo::all();
        foreach($nodos as $nodo){
            $componenteId = $nodo->componente->id;

            $result = Registro::select('unidad_id', DB::raw('MAX(Marca) as max_marca'), DB::raw('MIN(Marca) as min_marca'))
            ->where('componente_id', $componenteId)
            ->whereDate('created_at', '>=', now()->subDay())
            ->groupBy('unidad_id')
            ->get();

            return  $result;
        }
    }






    public function realTime(){

        $data = [
            'name' => "Rodrigo Castro",
            'dispositivo' => "Dispositivo K4",
            'proceso' => "Hola",
            'motivo' => "SSIISISI",
            'fecha' => "15/02/2023 13:23 pm"
        ];


        Mail::send('emails.alarma', $data, function ($message) {
            $message->to("snowitxc@gmail.com")->subject('Nueva Alarma');
        });


        return response()->json(["status" => "ok"], 200);

    }

    private function generarRegistros(){
        $nodos = Nodo::all();

        foreach($nodos as $nodo){
            $componente = $nodo->componente;
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
                         $newRegistro->unidad->unidad . "(" . $newRegistro->unidad->nombre . ") por debajo del rango de " . $componente->min . " " .
                          $newRegistro->unidad->unidad . " a " . $componente->max . " " . $newRegistro->max;
                    }else{
                        $motivo = "El Dispositivo $componente->Nombre ha marcado " . $dataValue . " " .
                         $newRegistro->unidad->unidad . "(" . $newRegistro->unidad->nombre . ") por debajo del rango de " . $componente->min . " " .
                          $newRegistro->unidad->unidad . " a " . $componente->max . " " . $newRegistro->max;
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


    //esta funcion escribira un xml con un valor de una medida por encima del rango especificado , por lo que el sistema luego debera generar una alarma.
    public function writeXmlAlarm(){
            $componente = Componente::find(38);
            $unidades =  $componente->unidades;
            $marcas  = array();
            foreach($unidades as $unidad){
                $min =  $unidad->pivot->min;
                $max = $unidad->pivot->max;
                $fecha = $unidad->pivot->created_at;
                array_push($marcas , [
                    "unidadId" => $unidad->id,
                    "unidadNombre" => $unidad->nombre,
                    "marca" => $max + 20,
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


    public function activityProcess(){
        $data = (array)Helper::processesActivityLastHour();
        broadcast(new UpdateProcessesActivity($data));
        return response()->json($data,200);

    }

}
