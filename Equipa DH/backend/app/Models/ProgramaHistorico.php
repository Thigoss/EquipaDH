<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de programas
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class ProgramaHistorico extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'programas_historicos';
 
    protected $fillable = [
        'user_id',
        'programa_id',
        'observacao',
        'nome',
        'descricao',
        'logomarca',
        'instituicao_id',
        'contexto_id',
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id', 'id');
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
}
