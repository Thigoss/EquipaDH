<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdesoesHistoricos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adesoes_historicos', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('adesao_id');
            $table->char('tipo', 2);
            $table->char('situacao', 2);
            $table->longText('justificativa')->nullable();
            $table->string('arquivo', 255)->nullable();
            $table->string('ciclo', 10)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('adesao_id')->references('id')->on('adesoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adesoes_historicos');
    }
}
