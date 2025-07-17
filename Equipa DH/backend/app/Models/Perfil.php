<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de perfis
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class Perfil extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'perfis';
 
    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'tipo',
        'ativo'
    ];

}
