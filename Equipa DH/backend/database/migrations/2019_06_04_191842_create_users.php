<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('instituicao_id')->nullable();
            $table->char('tipo', 1)->default(User::TP_EXTERNO)->nullable();
            $table->string('cpf', 20)->unique();
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
            $table->boolean('ativo')->boolean(true);
            $table->string('password', 255);
            $table->string('token', 255);
            $table->timestamps();

            $table->foreign('sexo_id')->references('id')->on('sexos');
            $table->foreign('orientacao_sexual_id')->references('id')->on('orientacoes_sexuais');
            $table->foreign('raca_id')->references('id')->on('racas');
            $table->foreign('escolaridade_id')->references('id')->on('escolaridades');
            $table->foreign('deficiencia_id')->references('id')->on('deficiencias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
