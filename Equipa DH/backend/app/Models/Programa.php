<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de programas
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class Programa extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'programas';
 
    protected $fillable = [
        'nome',
        'descricao',
        'logomarca',
        'unidade_id',
        'texto_confirmacao',
        'ativo'
    ];

    public function historicos()
    {
        return $this->hasMany(ProgramaHistorico::class, 'programa_id', 'id');
    }
    
    public function cronogramas()
    {
        return $this->hasMany(Cronograma::class, 'programa_id', 'id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id', 'id');
    }
}
