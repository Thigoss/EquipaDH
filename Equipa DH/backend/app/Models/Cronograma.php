<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de cronogramas
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class Cronograma extends BaseModel
{
    
    /**
     * Situações do cronograma
     */
    const ST_NAO_INICIADO = 'NI';

    const ST_EM_ANDAMENTO = 'AN';

    const ST_FINALIZADO = 'FI';

    const ST_CANCELADO = 'CA';

    /**
     * Fases do cronograma
     */
    const FA_NAO_INICIADO = 'NI';

    const FA_PUBLICACAO = 'PU';

    const FA_ADESAO = 'AD';

    const FA_FIM_ADESAO = 'AF';

    const FA_DIVULGACAO_ADESAO = 'DA';

    const FA_RECURSO_ADESAO = 'RA';

    const FA_FIM_RECURSO_ADESAO = 'RF';
    
    const FA_DIVULGACAO_HABILITADOS = 'DH';

    const FA_RECURSO_HABILITADOS = 'RH';

    const FA_FIM_RECURSO_HABILITADOS = 'HF';

    const FA_DIVULGACAO_LISTA = 'DL';

    const FA_ENCERRADO = 'EN';
    
    const FA_CONVOCACAO = 'CO';

    /**
     * Situações de publicação
     */

    const PU_PUBLICADO = 'PU';

    const PU_NAO_PUBLICADO = 'NP';

    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'cronogramas';
 
    protected $fillable = [
        'programa_id',
        'numero',
        'data_publicacao_inicio',
        'data_publicacao_fim',
        'data_adesao_inicio',
        'data_adesao_fim',
        'data_divulgacao_adesao_inicio',
        'data_divulgacao_adesao_fim',
        'data_recurso_adesao_inicio',
        'data_recurso_adesao_fim',
        'data_divulgacao_habilitados_inicio',
        'data_divulgacao_habilitados_fim',
        'data_recurso_habilitados_inicio',
        'data_recurso_habilitados_fim',
        'data_divulgacao_lista',
        'data_encerramento',
        'data_convocacao_inicio',
        'data_convocacao_fim',
        'fase',
        'publicacao',
        'situacao',
        'arquivo_cancelamento',
        'justificativa_cancelamento',
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id', 'id');
    }

}
