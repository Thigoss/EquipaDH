<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronogramasArquivosClassificacaoMunicipalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronogramas_arquivos_classificacao_municipal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cronograma_id');
            $table->string('nome', 255);
            $table->string('arquivo', 255);
            $table->boolean('ativo')->default(false);
            $table->foreign('cronograma_id')->references('id')->on('cronogramas');
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
        Schema::dropIfExists('cronogramas_arquivos_classificacao_municipal');
    }
}
