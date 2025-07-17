<?php

use App\Models\Escolaridade;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscolaridades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escolaridades', function(Blueprint $table){
            $table->increments('id');
            $table->string('nome', 255);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

       $escolaridades = [
            ['id' => 1,	 'nome' => 'Opções: Nao alfabetizado', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2,	 'nome' => '1° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3,	 'nome' => '2° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4,	 'nome' => '3° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5,	 'nome' => '4° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 6,	 'nome' => '5° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 7,	 'nome' => '6° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 8,	 'nome' => '7° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 9,	 'nome' => '8° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 10, 'nome' => '9° ano completo do ensino fundamental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 11, 'nome' => '1° ano completo do ensino médio', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 12, 'nome' => '2° ano completo do ensino medio', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 13, 'nome' => '3° ano completo do ensino médio', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 14, 'nome' => 'Ensino superior incompleto', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 15, 'nome' => 'Ensino superior completo', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
       ];

        foreach($escolaridades as $data) {
            \DB::table('escolaridades')->insert($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('escolaridades');
    }
}
