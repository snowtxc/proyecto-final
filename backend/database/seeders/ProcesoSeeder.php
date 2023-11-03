<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProcesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('procesos')->insert([
            'Nombre' => "Envasado de cerveza",
            'Descripcion' => "Envasado de cerveza",
        ]);

        DB::table('procesos')->insert([
            'Nombre' => "Producción de papel y Celulosa",
            'Descripcion' => "Producción de papel y celulosa",
        ]);

        DB::table('procesos')->insert([
            'Nombre' => "Control de plantas de tratamiento de agua",
            'Descripcion' => "Control de plantas de tratamiento de agua",
        ]);

        DB::table('procesos')->insert([
            'Nombre' => "Monitoreo de sistemas ferroviarios y de tránsito",
            'Descripcion' => "Monitoreo de sistemas ferroviarios y de tránsito",
        ]);

        DB::table('procesos')->insert([
            'Nombre' => "Monitoreo de oleoductos y gasoductos",
            'Descripcion' => "Monitoreo de oleoductos y gasoductos",
        ]);

        DB::table('procesos')->insert([
            'Nombre' => "Control de telares y maquinaria textil.",
            'Descripcion' => "Control de telares y maquinaria textil.",
        ]);





    }
}
