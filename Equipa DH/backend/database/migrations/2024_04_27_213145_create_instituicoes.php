<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('razao_social', 255);
            $table->string('cnpj', 30);
            $table->string('email', 255)->nullable();
            $table->string('telefone', 50)->nullable();
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

        Schema::create('instituicoes_responsaveis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('instituicao_id');
            $table->char('tipo', 1);
            $table->string('nome', 255);
            $table->string('telefone', 50);
            $table->string('email', 255);
            $table->timestamps();

            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
        });

        Schema::create('instituicoes_usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituicoes_usuarios');
        Schema::dropIfExists('instituicoes_responsaveis');
        Schema::dropIfExists('instituicoes');
    }
}
