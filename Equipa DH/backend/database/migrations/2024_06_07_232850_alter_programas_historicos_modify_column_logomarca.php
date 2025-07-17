<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProgramasHistoricosModifyColumnLogomarca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programas_historicos', function (Blueprint $table) {
            $table->string('logomarca', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programas_historicos', function (Blueprint $table) {
            $table->string('logomarca', 255)->change();
        });
    }
}
