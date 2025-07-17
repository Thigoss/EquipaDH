<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de adesões
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class Adesao extends BaseModel
{
    
    /**
     * Situações da adesão
     */
    const ST_PENDENTE = 'PE';

    const ST_DEVOLVIDO = 'DV';

    const ST_RECUSADO = 'RE';

    const ST_APROVADO = 'AP';

    const ST_CONVOCADO = 'CV';

    /**
     * Tipos da situação da adesão
     */

    const TP_ADESAO = 'AD';

    const TP_RECURSO_ADESAO = 'RA';

    const TP_RECURSO_CLASSIFICACAO = 'RC';

    const TP_CONVOCACAO = 'CV';

    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'adesoes';
 
    protected $fillable = [
        'user_id',
        'instituicao_id',
        'cronograma_id',
        'arquivo',
        'tipo',
        'situacao',
        'justificativa',
        'habilitado',
        'media_classificacao',
        'classificacao',
        'convocado',
        'termo_convocacao',
        'ciclo',
        'formulario_habilitacao'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id', 'id');
    }

    public function cronograma()
    {
        return $this->belongsTo(Cronograma::class, 'cronograma_id', 'id');
    }

    public function historico()
    {
        return $this->hasMany(AdesaoHistorico::class, 'adesao_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return (new \DateTime($value))->format('d/m/Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return (new \DateTime($value))->format('d/m/Y H:i');
    }
}
