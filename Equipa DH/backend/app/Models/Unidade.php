<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de unidades
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class Unidade extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'unidades';
 
    protected $fillable = [
        'nome',
        'estado_id',
        'cidade_id',
        'cep',
        'endereco',
        'bairro',
        'numero',
        'complemento',
        'ativo'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class, 'cidade_id', 'id');
    }

    public function responsaveis()
    {
        return $this->hasMany(UnidadeResponsavel::class, 'unidade_id', 'id');
    }
}
