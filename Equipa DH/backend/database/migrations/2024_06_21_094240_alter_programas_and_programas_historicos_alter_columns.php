<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProgramasAndProgramasHistoricosAlterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->text('nome')->nullable()->change();
            $table->text('descricao')->nullable()->change();
        });

        Schema::table('programas_historicos', function (Blueprint $table) {
            $table->text('nome')->nullable()->change();
            $table->text('descricao')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
