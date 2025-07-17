<?php

namespace App\Models;

use App\Helpers\EncryptHelper;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de representante de insituição
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class InstituicaoRepresentante extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'instituicoes_responsaveis';
 
    protected $fillable = [
        'instituicao_id',
        'tipo',
        'nome',
        'telefone',
        'email'
    ];

    public $encryptedAttributes = [
        'nome',
        'telefone',
        'email'
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id', 'id');
    }

    public function setNomeAttribute ($value) {
        if ($value) $this->attributes['nome'] = EncryptHelper::encrypt($value);
    }

    public function getNomeAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setTelefoneAttribute ($value) {
        if ($value) $this->attributes['telefone'] = EncryptHelper::encrypt($value);
    }

    public function getTelefoneAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setEmailAttribute ($value) {
        if ($value) $this->attributes['email'] = EncryptHelper::encrypt($value);
    }

    public function getEmailAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }
}
