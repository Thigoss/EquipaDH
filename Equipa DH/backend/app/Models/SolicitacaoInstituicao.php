<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de solicitações de instituições
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class SolicitacaoInstituicao extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'solicitacoes_instituicoes';
 
    protected $fillable = [
        'solicitacao_id',
        'razao_social',
        'cnpj',
        'esfera',
        'email',
        'telefone',
        'estado_id',
        'cidade_id',
        'cep', 
        'endereco',
        'bairro',
        'numero',
        'complemento'
    ];

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacao::class, 'solicitacao_id', 'id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class, 'cidade_id', 'id');
    }
}
