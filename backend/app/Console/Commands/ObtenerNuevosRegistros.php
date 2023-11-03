<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Componente;
use  App\Events\appendRegistrosDevice;



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


        foreach($result as $deviceRow){
            $deviceId = (string) $deviceRow->device->id;
            $componente = Componente::find($deviceId);
            $etapa = $componente->etapa;
            $proceso = $etapa->proceso;

            $registrosCreateds = array();
            foreach ($deviceRow->device->data as $data) {
                $dataId = (int) $data['id'];
                $dataValue = (float) $data->datavalue;
                $dataUnit = (string) $data->dataunit;
                $dataTime = (string) $data->datatime;


                $newRegistro = $componente->registros()->create([
                    "Marca" => $dataValue,
                    "created_at" => $dataTime,
                    "etapa_id"  => $etapa->id,
                    "componente_id" => $componente->id
                ]);

                array_push($registrosCreateds, [
                    "id" => $newRegistro->id,
                    "fechaHora" => $newRegistro->created_at,
                    "marca"  => $newRegistro->Marca,
                    "unidad" => "°C",
                    "proceso" => $proceso->Nombre,
                    "etapa"  => $etapa->Nombre
                ]);

            }
            broadcast(new appendRegistrosDevice($componente->id, $registrosCreateds));
        }
    }
}
