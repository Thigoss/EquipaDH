<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConfiguracoesGeraisAddArquivoHabilitacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracoes_gerais', function (Blueprint $table) {
            $table->string('arquivo_habilitacao', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracoes_gerais', function (Blueprint $table) {
            $table->dropColumn('arquivo_habilitacao');
        });
    }
}
