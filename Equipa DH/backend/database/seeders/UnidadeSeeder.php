<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conselho;

class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i=0; $i < 20; $i++) {
            $unidades = [
                    'nome_conselho' => 'Coselho ' . $i,
                    'data_criacao' => date('Y-m-d'),
                    'tipo_conselho_id' => 1,
                    'situacao_id' => 1,
                    'telefone' => '5199588-1244',
                    'email' => 'conselho'.$i.'@gmail.com',
                    'cep' => '93010080',
                    'estado_id' => 21,
                    'cidade_id' => 4251,
                    'endereco' => 'Rua Lindolfo Collor',
                    'numero' => '125',
                    'estado_abrangencia_id' => 21,
                    'cidade_abrangencia_id' => 4178
            ];

            $classe = new Conselho();
            
            $classe->nome_conselho = $unidades['nome_conselho'];
            $classe->data_criacao = $unidades['data_criacao'];
            $classe->tipo_conselho_id = $unidades['tipo_conselho_id'];
            $classe->situacao_id = $unidades['situacao_id'];
            $classe->telefone = $unidades['telefone'];
            $classe->email = $unidades['email'];
            $classe->cep = $unidades['cep'];
            $classe->estado_id = $unidades['estado_id'];
            $classe->cidade_id = $unidades['cidade_id'];
            $classe->endereco = $unidades['endereco'];
            $classe->numero = $unidades['numero'];
            $classe->estado_abrangencia_id = $unidades['estado_abrangencia_id'];
            $classe->cidade_abrangencia_id = $unidades['cidade_abrangencia_id'];
    
            $classe->save();
        }
    }
}
