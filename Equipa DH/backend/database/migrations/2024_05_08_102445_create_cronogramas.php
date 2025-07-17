<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronogramas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronogramas', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('programa_id');
            $table->string('numero', 255);
            $table->date('data_publicacao_inicio')->nullable();
            $table->date('data_publicacao_fim')->nullable();
            $table->date('data_adesao_inicio')->nullable();
            $table->date('data_adesao_fim')->nullable();
            $table->date('data_divulgacao_adesao_inicio')->nullable();
            $table->date('data_divulgacao_adesao_fim')->nullable();
            $table->date('data_recurso_adesao_inicio')->nullable();
            $table->date('data_recurso_adesao_fim')->nullable();
            $table->date('data_divulgacao_habilitados_inicio')->nullable();
            $table->date('data_divulgacao_habilitados_fim')->nullable();
            $table->date('data_recurso_habilitados_inicio')->nullable();
            $table->date('data_recurso_habilitados_fim')->nullable();
            $table->date('data_divulgacao_lista')->nullable();
            $table->date('data_encerramento')->nullable();
            $table->date('data_convocacao_inicio')->nullable();
            $table->date('data_convocacao_fim')->nullable();
            $table->char('fase', 2);
            $table->char('publicacao', 2);
            $table->char('situacao', 2);
            $table->string('arquivo_cancelamento', 255)->nullable();
            $table->longText('justificativa_cancelamento')->nullable();
            $table->timestamps();
            
            $table->foreign('programa_id')->references('id')->on('programas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cronogramas');
    }
}
