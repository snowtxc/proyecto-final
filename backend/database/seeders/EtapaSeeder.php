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
            'Nombre' => "Envasado",
            'proceso_id' => 1,
        ]);
    }
}
