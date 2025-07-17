<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdesoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adesoes', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('cronograma_id');
            $table->string('arquivo', 255);
            $table->char('tipo', 2);
            $table->char('situacao', 2);
            $table->longText('justificativa')->nullable();
            $table->boolean('habilitado')->default(false);
            $table->string('classificacao', 10)->nullable();
            $table->boolean('convocado')->default(false);
            $table->string('termo_convocacao', 255)->nullable();
            $table->string('ciclo', 10)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            $table->foreign('cronograma_id')->references('id')->on('cronogramas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adesoes');
    }
}
