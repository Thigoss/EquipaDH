<?php

namespace App\Models;

use App\Helpers\EncryptHelper;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de responsáveis das unidades
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class UnidadeResponsavel extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'unidades_responsaveis';
 
    protected $fillable = [
        'unidade_id',
        'nome',
        'telefone',
        'email'
    ];

    public $encryptedAttributes = [
        'nome',
        'telefone',
        'email'
    ];

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id', 'id');
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
