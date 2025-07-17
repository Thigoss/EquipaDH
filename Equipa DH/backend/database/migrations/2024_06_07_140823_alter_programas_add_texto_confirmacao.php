<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProgramasAddTextoConfirmacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->text('texto_confirmacao')->nullable();
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
            $table->dropColumn('texto_confirmacao');
        });
    }
}
