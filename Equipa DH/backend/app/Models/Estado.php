<?php

namespace App\Models;

use App\Helpers\EncryptHelper;
use Illuminate\Database\Eloquent\Model;

class Estado extends BaseModel
{
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'estados';

    public $timestamps = false;

    protected $fillable = [
        'estado',
        'sigla',
        'regiao'
    ];

    public $encryptedAttributes = [
        'estado',
        'sigla',
        'regiao'
    ];

    public function cidades()
    {
        return $this->hasMany(Cidade::class, 'estado_id', 'id');
    }

    public function setEstadoAttribute ($value) {
        if ($value) $this->attributes['estado'] = EncryptHelper::encrypt($value);
    }

    public function getEstadoAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setSiglaAttribute ($value) {
        if ($value) $this->attributes['sigla'] = EncryptHelper::encrypt($value);
    }

    public function getSiglaAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setRegiaoAttribute ($value) {
        if ($value) $this->attributes['regiao'] = EncryptHelper::encrypt($value);
    }

    public function getRegiaoAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }
}
