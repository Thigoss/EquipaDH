<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEscolaridadesDominio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $escolaridades = [
            ['id' => 1, 'nome' => 'Ensino fundamental incompleto', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'nome' => 'Ensino fundamental completo', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'nome' => 'Ensino médio incompleto', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'nome' => 'Ensino médio completo', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'nome' => 'Ensino superior incompleto', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 6, 'nome' => 'Ensino superior completo', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 7, 'nome' => 'Pós-graduação', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 8, 'nome' => 'Não alfabetizado', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        ];
   
        foreach($escolaridades as $data) {
            \DB::table('escolaridades')->upsert($data, 'id');
        }

        $escolaridades = \DB::table('escolaridades')->where('id', '>', 8)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
