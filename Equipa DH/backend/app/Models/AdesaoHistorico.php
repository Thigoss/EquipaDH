<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de historico de adesões
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class AdesaoHistorico extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'adesoes_historicos';
 
    protected $fillable = [
        'user_id',
        'adesao_id',
        'tipo',
        'situacao',
        'justificativa',
        'arquivo',
        'ciclo',
        'anexo',
        'formulario_habilitacao',
        'instituicao_id',
        'contexto_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function adesao()
    {
        return $this->belongsTo(Adesao::class, 'adesao_id', 'id');
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
