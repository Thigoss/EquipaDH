<?php

use App\Models\Raca;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRacas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('racas', function(Blueprint $table){
            $table->increments('id');
            $table->string('nome', 255);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        $array = [
          ['id' => 1, 'nome' => 'Negro', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')],
          ['id' => 2, 'nome' => 'Pardo', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')],
          ['id' => 3, 'nome' => 'Amarelo', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')],
          ['id' => 4, 'nome' => 'Branco', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')],
          ['id' => 5, 'nome' => 'Quilombola', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')],
          ['id' => 6, 'nome' => 'Indígena', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')],
          ['id' => 7, 'nome' => 'Cigano', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')],
          ['id' => 8, 'nome' => 'Ribeirinho', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')],
          ['id' => 9, 'nome' => 'Outros', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'),  'updated_at' => date('Y-m-d H:i:s')]
        ];

        foreach($array as $data) {
            \DB::table('racas')->insert($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('racas');
    }
}
