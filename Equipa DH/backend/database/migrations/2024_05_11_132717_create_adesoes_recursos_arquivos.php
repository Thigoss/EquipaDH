<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdesoesRecursosArquivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adesoes_recursos_arquivos', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('adesao_recurso_id');
            $table->string('nome', 255);
            $table->string('arquivo', 255);
            $table->timestamps();

            $table->foreign('adesao_recurso_id')->references('id')->on('adesoes_recursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adesoes_recursos_arquivos');
    }
}
