<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAdesoesAddFormularioHabilitacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adesoes', function (Blueprint $table) {
            $table->string('formulario_habilitacao', 255)->nullable();
        });

        Schema::table('adesoes_historicos', function (Blueprint $table) {
            $table->string('formulario_habilitacao', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adesoes', function (Blueprint $table) {
            $table->dropColumn('formulario_habilitacao');
        });

        Schema::table('adesoes_historicos', function (Blueprint $table) {
            $table->dropColumn('formulario_habilitacao');
        });
    }
}
