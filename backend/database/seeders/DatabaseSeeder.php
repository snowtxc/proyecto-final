<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'pedritodiestro@gmail.com',
            'password' => '$2a$12$f5kKlpfaueCkN0LPg0P.huWG6Lsi3m/29z8BK7vHlROobcB08BKzO',
            'rol' => 'Administrador',
            'email_verified_at' => now()
        ]);

        $this->call(UnidadesSeeder::class);

        $this->call(TipoComponenteSeeder::class);

        $this->call(ComponenteSeeder::class);

        $this->call(ComponenteUnidadsSeeder::class);

        $this->call(ProcesoSeeder::class);

        $this->call(EtapaSeeder::class);

        $this->call(RegistroSeeder::class);

        $this->call(NodoSeeder::class);

        $this->call(LinkSeeder::class);

        $this->call(ParteSeeder::class);

        $this->call(ParteNotasSeeder::class);

        $this->call(ProcesoUserSeeder::class);
    }
}
