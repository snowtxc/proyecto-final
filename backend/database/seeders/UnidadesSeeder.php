<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unidades')->insert([
            'nombre' => "Temperatura",
            'unidad' => "°C",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Humedad",
            'unidad' => "%",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Presion",
            'unidad' => "hPa",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Gramos por metro cúbico",
            'unidad' => "g/m³",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Litros por segundo",
            'unidad' => "L/s",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Metros",
            'unidad' => "m",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Metros",
            'unidad' => "m/s",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Amperios",
            'unidad' => "A",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Voltios",
            'unidad' => "V",
        ]);

        DB::table('unidades')->insert([
            'nombre' => "Vatios",
            'unidad' => "W",
        ]);
    }
}
