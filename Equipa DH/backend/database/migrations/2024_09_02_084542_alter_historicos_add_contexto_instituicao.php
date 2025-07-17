<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHistoricosAddContextoInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programas_historicos', function (Blueprint $table) {
            $table->unsignedBigInteger('instituicao_id')->nullable();
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');

            $table->unsignedBigInteger('contexto_id')->nullable();
            $table->foreign('contexto_id')->references('id')->on('perfis_users');
        });

        Schema::table('solicitacoes_historicos', function (Blueprint $table) {
            $table->unsignedBigInteger('instituicao_id')->nullable();
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');

            $table->unsignedBigInteger('contexto_id')->nullable();
            $table->foreign('contexto_id')->references('id')->on('perfis_users');
        });

        Schema::table('adesoes_historicos', function (Blueprint $table) {
            $table->unsignedBigInteger('instituicao_id')->nullable();
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');

            $table->unsignedBigInteger('contexto_id')->nullable();
            $table->foreign('contexto_id')->references('id')->on('perfis_users');
        });
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
