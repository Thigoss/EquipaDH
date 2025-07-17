<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BemEquipamento extends Model
{
    protected $table = 'bem_equipamentos';

    protected $fillable = [
        'nome', 'categoria', 'descricao', 'situacao' 
    ];
}
