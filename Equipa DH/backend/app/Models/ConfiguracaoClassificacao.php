<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de configurações de classificações
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class ConfiguracaoClassificacao extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'configuracoes_classificacoes';
 
    protected $fillable = [
        'nome',
        'arquivo',
        'situacao_carregamento',
        'ativo'
    ];

}
