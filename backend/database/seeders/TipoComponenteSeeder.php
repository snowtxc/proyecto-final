<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComponenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_componentes')->insert([
            'Nombre' => "Bomba de Agua",
            'Imagen' => "public/tipos-componentes/bomba-de-agua.png",

        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Maquina de Llenado",
            'Imagen' => "public/tipos-componentes/filling-machine.png",
        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Sensor de Gas",
            'Imagen' => "public/tipos-componentes/gas.png",
        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Horno Electrico",
            'Imagen' => "public/tipos-componentes/horno-de-arco-electrico.png",
        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Sensor de Humedad",
            'Imagen' => "public/tipos-componentes/humidity.png",
        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Sensor de Luz",
            'Imagen' => "public/tipos-componentes/light_sensor.png",
        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Motor Electrico",
            'Imagen' => "public/tipos-componentes/motor-electrico.png",
        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Panel Tactil",
            'Imagen' => "public/tipos-componentes/panel-tactil.png",
        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Sensor de Presion",
            'Imagen' => "public/tipos-componentes/presion.png",
        ]);


        DB::table('tipo_componentes')->insert([
            'Nombre' => "Valvula",
            'Imagen' => "public/tipos-componentes/valvula.png",
        ]);

        DB::table('tipo_componentes')->insert([
            'Nombre' => "Sensor de temperatura",
            'Imagen' => "public/tipos-componentes/temperature.png",
        ]);

    }
}
