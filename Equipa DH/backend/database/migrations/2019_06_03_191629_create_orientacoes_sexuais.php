<?php

use App\Models\OrientacaoSexual;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrientacoesSexuais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orientacoes_sexuais', function(Blueprint $table){
            $table->increments('id');
            $table->string('nome', 255);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        $array = [
            ['id' => 1, 'nome' => 'Gay', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'nome' => 'Heterossexual', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'nome' => 'Lésbica', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'nome' => 'Bissexual', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'nome' => 'Prefiro não declarar', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 6, 'nome' => 'Outros', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        ];

        \DB::table('orientacoes_sexuais')->insert($array);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orientacoes_sexuais');
    }
}
