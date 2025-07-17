<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('users')->insert([
            'tipo' => User::TP_INTERNO,
            'cpf' => '11111111111',
            'email' => 'usuario@teste.com',
            'nome' => 'Usuário Teste',
            'password' => bcrypt('123456'),
            'token' => '*',
            'ativo' => true,
        ]);
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
