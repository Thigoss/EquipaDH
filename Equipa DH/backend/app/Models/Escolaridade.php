<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escolaridade extends BaseModel
{
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'escolaridades';
    
    protected $fillable = [
        'nome',
        'ativo'
    ];
}
