<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersEncryptedColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('cpf')->nullable()->change();
            $table->text('email')->nullable()->change();
            $table->text('nome')->nullable()->change();
            $table->text('nome_social')->nullable()->change();
            $table->text('data_nascimento')->nullable()->change();
            $table->text('sexo_outro')->nullable()->change();
            $table->text('orientacao_sexual_outro')->nullable()->change();
            $table->text('cargo')->nullable()->change();
            $table->text('telefone_funcional')->nullable()->change();
            $table->text('celular')->nullable()->change();
            $table->text('email_funcional')->nullable()->change();
            $table->text('documento_rg')->nullable()->change();
            $table->text('documento_cpf')->nullable()->change();
            $table->text('documento_ato_posse')->nullable()->change();
            $table->text('documento_ato_delegacao')->nullable()->change();
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
