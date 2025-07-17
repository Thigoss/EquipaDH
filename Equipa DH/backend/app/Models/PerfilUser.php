<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de relacionamento entre usuários, perfis e unidades
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class PerfilUser extends BaseModel
{
    const TP_ADMIN = 1;
    const TP_PADRAO = 2;
    const TP_SOLICITANTE = 3;
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'perfis_users';

    public $timestamps = false;
 
    protected $fillable = [
        'perfil_id',
        'user_id',
        'unidade_id',
        'ativo'
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'perfil_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id', 'id');
    }
}
