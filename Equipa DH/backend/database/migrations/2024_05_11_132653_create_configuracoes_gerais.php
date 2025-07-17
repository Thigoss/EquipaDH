<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracoesGerais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracoes_gerais', function(Blueprint $table){
            $table->increments('id');
            $table->longText('orientacoes_credenciamento')->nullable();
            $table->string('arquivo_ato_declaracao', 255)->nullable();
            $table->string('arquivo_ato_delegacao', 255)->nullable();
            $table->longText('orientacoes_adesao')->nullable();
            $table->longText('orientacoes_solicitacao')->nullable();
            $table->string('arquivo_declaracao_unificada', 255)->nullable();
            $table->string('arquivo_ato_convocacao', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracoes_gerais');
    }
}
