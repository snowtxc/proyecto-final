<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        DB::table('links')->insert([
            'nodo_from_id' => 1,
            'nodo_to_id' => 2,
            'etapa_id' => 16
        ]);
        //2
       
        DB::table('links')->insert([
            'nodo_from_id' => 4,
            'nodo_to_id' => 2,
            'etapa_id' => 16
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 3,
            'nodo_to_id' => 2,
            'etapa_id' => 16
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 6,
            'nodo_to_id' => 5,
            'etapa_id' => 17
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 5,
            'nodo_to_id' => 7,
            'etapa_id' => 17
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 7,
            'nodo_to_id' => 8,
            'etapa_id' => 17
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 9,
            'nodo_to_id' => 10,
            'etapa_id' => 18
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 10,
            'nodo_to_id' => 11,
            'etapa_id' => 18
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 11,
            'nodo_to_id' => 12,
            'etapa_id' => 18
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 15,
            'nodo_to_id' => 14,
            'etapa_id' => 19
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 15,
            'nodo_to_id' => 13,
            'etapa_id' => 19
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 16,
            'nodo_to_id' => 17,
            'etapa_id' => 20
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 17,
            'nodo_to_id' => 18,
            'etapa_id' => 20
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 20,
            'nodo_to_id' => 19,
            'etapa_id' => 21
        ]);

        DB::table('links')->insert([
            'nodo_from_id' => 19,
            'nodo_to_id' => 21,
            'etapa_id' => 21
        ]);
    }
}
