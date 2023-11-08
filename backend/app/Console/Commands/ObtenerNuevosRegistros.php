<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Componente;
use App\Models\Registro;
use App\Models\Alarma;
use App\Models\AlarmaUser;

use  App\Events\appendRegistrosDevice;
use  App\Events\PushAlarmaNotificacion;

use Illuminate\Support\Facades\Mail;



class ObtenerNuevosRegistros extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:obtener-nuevos-registros';

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

    private function generarAlarma($componente,$motivo){
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

            Mail::send('emails.alarma', $data, function ($message) use ($usuario) {
                $message->to($usuario->email)->subject('Nueva Alarma');
            });

            $alarmaUser = new AlarmaUser;
            $alarmaUser->alarma_id = $alarma->id;
            $alarmaUser->user_id = $usuario->id;
            $alarmaUser->save();

            broadcast(new PushAlarmaNotificacion($usuario->id, $data));

        }
    }

    public function handle()
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
                ftp_delete($ftpConnection, $remoteFilePath);
                ftruncate($memoryStream, 0);
                ftp_close($ftpConnection);

            }

        }

        $alarmas = array();
        foreach($result as $deviceRow){
            $deviceId =  $deviceRow->device->id;
            $componente = Componente::find($deviceId);

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

                if($dataValue < $componente->min ||  $dataValue > $componente->max){
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
}
