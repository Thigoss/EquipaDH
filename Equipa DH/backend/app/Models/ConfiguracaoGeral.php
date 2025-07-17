<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de configurações gerais
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class ConfiguracaoGeral extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'configuracoes_gerais';
 
    protected $fillable = [
        'orientacoes_credenciamento',
        'arquivo_ato_declaracao',
        'arquivo_ato_delegacao',
        'orientacoes_adesao',
        'orientacoes_solicitacao',
        'arquivo_declaracao_unificada',
        'arquivo_ato_convocacao',
        'arquivo_habilitacao'
    ];

}
