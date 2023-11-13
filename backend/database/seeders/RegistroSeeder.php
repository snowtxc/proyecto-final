<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class RegistroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');


        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');

        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 3,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');


        $mutable = Carbon::now();
        DB::table('registros')->insert([
            'componente_id' => 2,
            'Marca' => 40,
            'created_at' => $mutable,
            'etapa_id' => 2,
            'unidad_id' => 3
        ]);
        $mutable->add(1, 'day');
    }
}
