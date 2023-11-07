<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class EtapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('etapas')->insert([
            'Nombre' => "Elaboracion",
            'proceso_id' => 1,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Preparación de la cerveza",
            'proceso_id' => 1,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Clarificación y filtrado",
            'proceso_id' => 1,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Clarificación y filtrado",
            'proceso_id' => 1,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Cocción o Cocido de la Madera",
            'proceso_id' => 2,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Preparación de la Mezcla de Pasta",
            'proceso_id' => 2,
        ]);


        DB::table('etapas')->insert([
            'Nombre' => "Secado",
            'proceso_id' => 2,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Captacion de Agua",
            'proceso_id' => 3,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Pretratamiento",
            'proceso_id' => 3,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Tratamiento Quimico",
            'proceso_id' => 3,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Control de Energía y Electrificación",
            'proceso_id' => 4,
        ]);


        DB::table('etapas')->insert([
            'Nombre' => "Supervision de Estaciones",
            'proceso_id' => 4,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Control de Energía y Electrificación",
            'proceso_id' => 4,
        ]);

        DB::table('etapas')->insert([
            'Nombre' =>  "Supervisión de Presión y Flujo",
            'proceso_id' => 5,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Gestion de Valvulas",
            'proceso_id' => 5,
        ]);


        DB::table('etapas')->insert([
            'Nombre' => "Supervisión de Telares y Maquinaria",
            'proceso_id' => 6,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Control de Hilos y Enhebrado",
            'proceso_id' => 6,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Monitoreo del estado de los hilos y sistemas de enhebrado.",
            'proceso_id' => 6,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Control de la distribución y tensión de los hilos..",
            'proceso_id' => 6,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Control de Temperatura y Humedad",
            'proceso_id' => 6,
        ]);

        DB::table('etapas')->insert([
            'Nombre' => "Control de Rodillos y Dispositivos de Estiramiento.",
            'proceso_id' => 6,
        ]);
    }
}
