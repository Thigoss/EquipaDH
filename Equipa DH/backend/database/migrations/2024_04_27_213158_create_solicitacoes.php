<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('instituicao_id')->nullable();
            $table->string('cpf', 20);
            $table->string('email')->nullable();
            $table->string('nome');
            $table->string('nome_social')->nullable();
            $table->dateTime('data_nascimento')->nullable();
            $table->unsignedInteger('sexo_id')->nullable();
            $table->string('sexo_outro', 255)->nullable();
            $table->unsignedInteger('orientacao_sexual_id')->nullable();
            $table->string('orientacao_sexual_outro', 255)->nullable();
            $table->unsignedInteger('raca_id')->nullable();
            $table->unsignedInteger('escolaridade_id')->nullable();
            $table->boolean('possui_deficiencia')->nullable();
            $table->unsignedInteger('deficiencia_id')->nullable();
            $table->char('tipo_representacao', 1)->nullable();
            $table->string('cargo', 255)->nullable();
            $table->string('telefone_funcional', 255)->nullable();
            $table->string('celular', 255)->nullable();
            $table->string('email_funcional', 255)->nullable();
            $table->string('documento_rg', 255)->nullable();
            $table->string('documento_cpf', 255)->nullable();
            $table->string('documento_ato_posse', 255)->nullable();
            $table->string('documento_ato_delegacao', 255)->nullable();
            $table->char('situacao', 2);
            $table->timestamps();

            $table->foreign('sexo_id')->references('id')->on('sexos');
            $table->foreign('orientacao_sexual_id')->references('id')->on('orientacoes_sexuais');
            $table->foreign('raca_id')->references('id')->on('racas');
            $table->foreign('escolaridade_id')->references('id')->on('escolaridades');
            $table->foreign('deficiencia_id')->references('id')->on('deficiencias');
        });

        Schema::create('solicitacoes_instituicoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('solicitacao_id');
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
            $table->timestamps();

            $table->foreign('solicitacao_id')->references('id')->on('solicitacoes');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->foreign('cidade_id')->references('id')->on('cidades');
        });

        Schema::create('solicitacoes_historicos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('solicitacao_id');
            $table->unsignedInteger('user_id');
            $table->string('situacao', 2);
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->foreign('solicitacao_id')->references('id')->on('solicitacoes');
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
        Schema::dropIfExists('solicitacoes_instituicoes');
        Schema::dropIfExists('solicitacoes_historicos');
        Schema::dropIfExists('solicitacoes');
    }
}
