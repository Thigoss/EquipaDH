<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de histórico de solicitações
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class SolicitacaoHistorico extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'solicitacoes_historicos';
 
    protected $fillable = [
        'solicitacao_id',
        'user_id',
        'situacao',
        'observacao',
        'anexo',
        'instituicao_id',
        'contexto_id',
    ];

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacao::class, 'solicitacao_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id', 'id');
    }

    public function contexto()
    {
        return $this->belongsTo(PerfilUser::class, 'contexto_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return (new \DateTime($value))->format('d/m/Y H:i');
    }
}
