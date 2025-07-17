<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->unsignedInteger('estado_id');
            $table->unsignedInteger('cidade_id');
            $table->string('cep', 20);
            $table->string('endereco', 255);
            $table->string('bairro', 255);
            $table->string('numero', 50);
            $table->string('complemento', 300)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('estado_id')->references('id')->on('estados');
            $table->foreign('cidade_id')->references('id')->on('cidades');
        });

        Schema::create('unidades_responsaveis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unidade_id');
            $table->string('nome', 255);
            $table->string('telefone', 50);
            $table->string('email', 255);
            $table->timestamps();

            $table->foreign('unidade_id')->references('id')->on('unidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidades_responsaveis');
        Schema::dropIfExists('unidades');
    }
}
