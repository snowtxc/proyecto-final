<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProcesoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proceso_user')->insert([
            'user_id' => 11,
            'proceso_id' => 1,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 2,
            'proceso_id' => 2,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 3,
            'proceso_id' => 2,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 4,
            'proceso_id' => 3,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 5,
            'proceso_id' => 3,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 6,
            'proceso_id' => 4,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 7,
            'proceso_id' => 4,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 8,
            'proceso_id' => 5,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 9,
            'proceso_id' => 5,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 10,
            'proceso_id' => 6,
        ]);

        DB::table('proceso_user')->insert([
            'user_id' => 1,
            'proceso_id' => 6,
        ]);
    }
}
