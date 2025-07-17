<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfisUsersTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfis_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('perfil_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('unidade_id')->nullable();

            $table->foreign('perfil_id')->references('id')->on('perfis');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('perfis_users');
    }
}
