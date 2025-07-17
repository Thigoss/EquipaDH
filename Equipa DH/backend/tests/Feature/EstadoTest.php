<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EstadoTest extends TestCase
{
    
    /**
     * Teste da api de retorno de estados
     *
     * @return void
     */
    public function test_get_estados()
    {
        $response = $this->get('/api/estado');

        $response->assertStatus(200)
                 ->assertJson([
                    'success' => true,
                    'msg' => '',
                    'data' => [
                        [
                            "id" => 1,
                            "estado" => "Acre",
                            "sigla" => "AC",
                            "regiao" => "Norte"
                        ]
                    ]
                 ]);
    }
}
