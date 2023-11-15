<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ParteNotasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la parte 1 del componente 1',
            'user_id' => 1,
            'parte_id' => 1
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la parte 2 del componente 2',
            'user_id' => 1,
            'parte_id' => 4
        ]);


        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la parte 1 del componente 4',
            'user_id' => 1,
            'parte_id' => 7
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 2 del compoente 5',
            'user_id' => 1,
            'parte_id' => 10
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 1 del compoente 7',
            'user_id' => 1,
            'parte_id' => 13
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 1 del compoente 8',
            'user_id' => 1,
            'parte_id' => 15
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 2 del compoente 9',
            'user_id' => 1,
            'parte_id' => 18
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 1 del compoente 11',
            'user_id' => 1,
            'parte_id' => 21
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 2 del compoente 12',
            'user_id' => 1,
            'parte_id' => 24
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 2 del compoente 13',
            'user_id' => 1,
            'parte_id' => 26
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 1 del compoente 15',
            'user_id' => 1,
            'parte_id' => 29
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 2 del compoente 16',
            'user_id' => 1,
            'parte_id' => 32
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 1 del compoente 18',
            'user_id' => 1,
            'parte_id' => 35
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 2 del compoente 19',
            'user_id' => 1,
            'parte_id' => 38
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 2 del compoente 20',
            'user_id' => 1,
            'parte_id' => 40
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 1 del compoente 22',
            'user_id' => 1,
            'parte_id' => 43
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 1 del compoente 23',
            'user_id' => 1,
            'parte_id' => 45
        ]);

        DB::table('parte_notas')->insert([
            'Descripcion' => 'Esta es la nota de la Parte 1 del compoente 24',
            'user_id' => 1,
            'parte_id' => 47
        ]);
    }
}
