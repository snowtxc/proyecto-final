<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use phpseclib\Net\SFTP;
use App\Models\Componente;
use App\Models\Registro;
use App\Models\Nodo;
use App\Models\Alarma;
use App\Models\AlarmaUser;
use SimpleXMLElement;
use Illuminate\Support\Facades\DB;


use App\Events\appendRegistrosDevice;
use App\Events\PushAlarmaNotificacion;

use Illuminate\Support\Facades\Mail;


class FtpController extends Controller
{

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
        return $xml->saveXml();
    }

    public function generarRegistros()
    {

        $ftpServer = env('FTP_HOST');
        $ftpUsername = env('FTP_USERNAME');
        $ftpPassword = env('FTP_PASSWORD');
        $remoteDirectory = '/data-x/x-input';

        $ftpConn = ftp_connect($ftpServer);
        $loginResult = ftp_login($ftpConn, $ftpUsername, $ftpPassword);

        if (!($ftpConn && $loginResult)) {
            return response()->json(["Ha ocurrido un error en la conexion al servidor ftp"]);
        }

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
            $xml = $this->generarXml($data);
            $remoteFilePath = $remoteDirectory . '/device'.$componente->id."-".time().".xml";

            ftp_pasv($ftpConn,TRUE);
            $tempFile = tempnam(sys_get_temp_dir(), 'xml_temp');
            file_put_contents($tempFile, $xml);

            $uploadResult = ftp_put($ftpConn, $remoteFilePath, $tempFile, FTP_BINARY);
            if ($uploadResult) {
                return response()->json(["Archivo subido con exito"]);
            } else {
                return response()->json(["Ha ocurrido un error"]);

            }
            unlink($tempFile);

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

}
