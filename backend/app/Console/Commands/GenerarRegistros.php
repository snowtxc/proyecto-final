<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use phpseclib\Net\SFTP;
use App\Models\Nodo;
use App\Models\Componente;
use SimpleXMLElement;



class GenerarRegistros extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generar-nuevos-registros';

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
        return $xml->saveXml();
    }

    public function handle()
    {

        $ftpServer = env('FTP_HOST');
        $ftpUsername = env('FTP_USERNAME');
        $ftpPassword = env('FTP_PASSWORD');
        $remoteDirectory = '/data-x/x-input';

        $ftpConn = ftp_connect($ftpServer);
        $loginResult = ftp_login($ftpConn, $ftpUsername, $ftpPassword);

        if (!($ftpConn && $loginResult)) {
            return;
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
                $this->info("Archivo subido correctamente");
            } else {
                $this->info("Archivo no se ha podido subir");

            }
            unlink($tempFile);

        }
        ftp_close($ftpConn);
    }
}
