<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class NodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        DB::table('nodos')->insert([
            'posicion' => '-711 -242',
            'etapa_id' => 16,
            'componente_id' => 1,
            'created_at' => now()
        ]);
        
        //2
       
        DB::table('nodos')->insert([
            'posicion' => '-425 -242',
            'etapa_id' => 16,
            'componente_id' => 5,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-425 -59',
            'etapa_id' => 16,
            'componente_id' => 9,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-425 -414',
            'etapa_id' => 16,
            'componente_id' => 18,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-261 -204',
            'etapa_id' => 17,
            'componente_id' => 3,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-524 -204',
            'etapa_id' => 17,
            'componente_id' => 7,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-152 -347',
            'etapa_id' => 17,
            'componente_id' => 15,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '21 -204',
            'etapa_id' => 17,
            'componente_id' => 21,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-432 -295',
            'etapa_id' => 18,
            'componente_id' => 4,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-254 -350',
            'etapa_id' => 18,
            'componente_id' => 10,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-70 -312',
            'etapa_id' => 18,
            'componente_id' => 22,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '146 -312',
            'etapa_id' => 18,
            'componente_id' => 24,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-198 -342',
            'etapa_id' => 19,
            'componente_id' => 8,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-519 -254',
            'etapa_id' => 19,
            'componente_id' => 2,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-198 -136',
            'etapa_id' => 19,
            'componente_id' => 14,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-453 -237',
            'etapa_id' => 20,
            'componente_id' => 19,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-278 -334',
            'etapa_id' => 20,
            'componente_id' => 16,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-15 -300',
            'etapa_id' => 20,
            'componente_id' => 23,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-355 -430',
            'etapa_id' => 21,
            'componente_id' => 12,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-549.75 -319',
            'etapa_id' => 21,
            'componente_id' => 20,
            'created_at' => now()
        ]);

        DB::table('nodos')->insert([
            'posicion' => '-203 -248',
            'etapa_id' => 21,
            'componente_id' => 6,
            'created_at' => now()
        ]);
    }
}
