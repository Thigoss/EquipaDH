<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de arquivos de classificação
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class ArquivoClassificacao extends BaseModel
{

    protected $connection = 'pgsql';

    public $table = 'arquivos_classificacao';

    public $save_audit_transaction = true;

    protected $fillable = [
        'nome',
        'caminho',
        'ativo'
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y H:i', strtotime($value));
    }
}
