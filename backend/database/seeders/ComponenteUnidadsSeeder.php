<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ComponenteUnidadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 1,
            'unidades_id' => 3,
            'min' => 30,
            'max' => 100
        ]);
        //2
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 2,
            'unidades_id' => 3,
            'min' => 20,
            'max' => 80
        ]);
        //3
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 3,
            'unidades_id' => 3,
            'min' => 10,
            'max' => 60
        ]);
        //4
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 4,
            'unidades_id' => 3,
            'min' => 20,
            'max' => 60
        ]);
        //5
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 5,
            'unidades_id' => 5,
            'min' => 10,
            'max' => 30
        ]);
        //6
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 6,
            'unidades_id' => 9,
            'min' => 10,
            'max' => 40
        ]);
        //7
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 7,
            'unidades_id' => 4,
            'min' => 1000,
            'max' => 2000
        ]);
        //8
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 8,
            'unidades_id' => 1,
            'min' => 10,
            'max' => 70
        ]);
        //9
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 9,
            'unidades_id' => 1,
            'min' => 10,
            'max' => 80
        ]);
        //10
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 10,
            'unidades_id' => 1,
            'min' => 20,
            'max' => 50
        ]);
        //11
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 11,
            'unidades_id' => 1,
            'min' => 30,
            'max' => 70
        ]);
        //12
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 12,
            'unidades_id' => 1,
            'min' => 110,
            'max' => 230
        ]);
        //13
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 13,
            'unidades_id' => 1,
            'min' => 10,
            'max' => 30
        ]);
        //14
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 14,
            'unidades_id' => 9,
            'min' => 80,
            'max' => 200
        ]);
        //15
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 15,
            'unidades_id' => 9,
            'min' => 100,
            'max' => 200
        ]);
        //16
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 16,
            'unidades_id' => 2,
            'min' => 10,
            'max' => 20
        ]);
        //17
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 17,
            'unidades_id' => 10,
            'min' => 10,
            'max' => 80
        ]);
        //18
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 18,
            'unidades_id' => 10,
            'min' => 20,
            'max' => 50
        ]);
        //19
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 19,
            'unidades_id' => 9,
            'min' => 100,
            'max' => 300
        ]);
        //20
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 20,
            'unidades_id' => 9,
            'min' => 100,
            'max' => 300
        ]);
        //21
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 21,
            'unidades_id' => 7,
            'min' => 50,
            'max' => 150
        ]);
        //22
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 22,
            'unidades_id' => 7,
            'min' => 50,
            'max' => 150
        ]);
        //23
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 23,
            'unidades_id' => 5,
            'min' => 10,
            'max' => 30
        ]);
        //24
        DB::table('componente_unidads')->insert([
            'created_at' => now(),
            'componente_id' => 24,
            'unidades_id' => 5,
            'min' => 10,
            'max' => 40
        ]);
    }
}
