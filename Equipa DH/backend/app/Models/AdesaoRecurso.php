<?php

namespace App\Models;

use App\Service\Auth\Type\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de recursos de adesões
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class AdesaoRecurso extends BaseModel
{
    
    /**
     * Tipos de recursos
     */
    const TP_ADESAO = 'RA';

    const TP_CLASSIFICACAO = 'RC';

    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'adesoes_recursos';
 
    protected $fillable = [
        'user_id',
        'adesao_id',
        'tipo',
        'justificativa'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function adesao()
    {
        return $this->belongsTo(Adesao::class, 'adesao_id', 'id');
    }

    public function arquivos()
    {
        return $this->hasMany(AdesaoRecursoArquivo::class, 'adesao_recurso_id', 'id');
    }
}
