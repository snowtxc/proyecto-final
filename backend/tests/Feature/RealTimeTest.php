<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RealTimeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = [
            'Nombre' => 'Example Name',
            'DireccionIp' => '127.0.0.1',
            'Descripcion' => 'Example Description',
            'tipo_componente_id' => 1,
            'unidades' => [
                [
                    "unidad_id" => 1,
                    "min" => 20,
                    "max" => 100
                ],
                [
                    "unidad_id" => 2,
                    "min" => 50,
                    "max" => 500
                ]

            ],
        ];

        $response = $this->json('POST', '/api/componentes', $data);

        $response->assertStatus(200)
            ->assertJson(['Nombre' => 'Example Name']); // Adjust the expected data as needed

        $this->assertDatabaseHas('componentes', ['Nombre' => 'Example Name']);
    }
}
