<?php

use App\Models\Deficiencia;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeficiencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deficiencias', function(Blueprint $table){
            $table->increments('id');
            $table->string('nome', 255);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        $array = [
            ['id' => 1, 'nome' => 'Auditiva', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'nome' => 'Visual', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'nome' => 'Mental', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'nome' => 'Física', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'nome' => 'Múltipla', 'ativo' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        ];

        foreach($array as $data) {
            \DB::table('deficiencias')->insert($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deficiencias');
    }
}
