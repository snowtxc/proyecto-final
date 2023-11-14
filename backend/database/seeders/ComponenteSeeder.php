<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ComponenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        DB::table('componentes')->insert([
            'Nombre' => "Bomba de Chorro J10S",
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 1
        ]);
        //2
        DB::table('componentes')->insert([
            'Nombre' => "Bomba de Agua de Paletas Giratorias Dynamo",
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 1
        ]);
        //3
        DB::table('componentes')->insert([
            'Nombre' => "Bomba de Émbolo o Pistón 5CP2120W",
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 1
        ]);
        //4
        DB::table('componentes')->insert([
            'Nombre' => "Bomba General Dump 5CP2120W",
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 1
        ]);

        //5
        DB::table('componentes')->insert([
            'Nombre' => "Llenadora de Gravedad GPF-8",
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 2
        ]);
        //6
        DB::table('componentes')->insert([
            'Nombre' => "Acasi Machinery: Modelo PF-12",
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 2
        ]);
        //7
        DB::table('componentes')->insert([
            'Nombre' => "Llenadora de Bolsas Mercury HS"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 2
        ]);

        //8
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Gas Infrarrojo S3KUL2-XNXXSS1SS"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 3
        ]);
        //9
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Gas Infrarrojo Gasman IR"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 3
        ]);

        //10
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Gas Infrarrojo Altair 4XR"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 3
        ]);

        //11
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Gas Infrarrojo S3KUL2-XNXXSS1SS"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 3
        ]);

        //12
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Gas o Ventis MX4"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 3
        ]);
        //13
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Gas PID Minrae 300"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 3
        ]);

        //14
        DB::table('componentes')->insert([
            'Nombre' => "Horno Electrico Blodgett Zephaire-200-G"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 4
        ]);
        //15
        DB::table('componentes')->insert([
            'Nombre' => "Horno Electrico Vulcan VC5GD"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 4
        ]);
        //16
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Humedad Omron  D6T"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 5
        ]);
        //17
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Luz LuxSense 2000"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 6
        ]);
        //18
        DB::table('componentes')->insert([
            'Nombre' => "Sensor de Luz BrightView Elite"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 6
        ]);
        //19
        DB::table('componentes')->insert([
            'Nombre' => "Motor Electrico PowerDrive 3000"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 7
        ]);
        //20
        DB::table('componentes')->insert([
            'Nombre' => "Motor Electrico  ElectraMax X7"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 7
        ]);
        //21
        DB::table('componentes')->insert([
            'Nombre' => "Motor Electrico TurboCharge E250"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 7
        ]);
        //22
        DB::table('componentes')->insert([
            'Nombre' => "Motor Electrico TurboCharge E250"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 7

        ]);
        //23
        DB::table('componentes')->insert([
            'Nombre' => "Válvula Flowguard Elite 600"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 8

        ]);
        //24
        DB::table('componentes')->insert([
            'Nombre' => "Válvula  TurboValve 300"            ,
            'Descripcion' => "Esto es una descripcion.",
            'Unidad' => '',
            'DireccionIp' => '192.168.0.200',
            'tipo_componente_id' => 8

        ]);














        /*

        DB::table('componentes')->insert([
            'Nombre' => "Llenadora de Gravedad GPF-8",
            'Descripcion' => "Control de telares y maquinaria textil.",
        ]);

        DB::table('componentes')->insert([
            'Nombre' => "Control de telares y maquinaria textil.",
            'Descripcion' => "Control de telares y maquinaria textil.",
        ]);*/

    }
}
