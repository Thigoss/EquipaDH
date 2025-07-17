<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CidadeTest extends TestCase
{
    /**
     * Teste da api de retorno de cidades
     *
     * @return void
     */
    public function test_get_cidade_by_uf()
    {
        $response = $this->get('/api/cidade/7');

        $response->assertStatus(200)
                 ->assertJson([
                    'success' => true,
                    'msg' => '',
                    'data' => [
                        [
                            'id' => 804,
                            'cidade' => 'Brasília',
                            'estado_id' => 7
                        ]
                    ]
                 ]);
    }
}
