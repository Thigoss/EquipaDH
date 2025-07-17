<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTabelasAuxiliaresColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estados', function (Blueprint $table) {
            $table->text('estado')->nullable()->change();
            $table->text('sigla')->nullable()->change();
            $table->text('regiao')->nullable()->change();
        });

        Schema::table('cidades', function (Blueprint $table) {
            $table->text('cidade')->nullable()->change();
            $table->text('codigo_ibge')->nullable()->change();
        });

        Schema::table('orientacoes_sexuais', function (Blueprint $table) {
            $table->text('nome')->nullable()->change();
        });

        Schema::table('racas', function (Blueprint $table) {
            $table->text('nome')->nullable()->change();
        });

        Schema::table('sexos', function (Blueprint $table) {
            $table->text('nome')->nullable()->change();
        });

        Schema::table('deficiencias', function (Blueprint $table) {
            $table->text('nome')->nullable()->change();
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
