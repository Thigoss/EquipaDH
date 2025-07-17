<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProgramasAddInstituicaoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->unsignedInteger('unidade_id')->nullable();
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
        Schema::table('programas', function (Blueprint $table) {
            $table->dropForeign(['unidade_id']);
            $table->dropColumn('unidade_id');
        });
    }
}
