<?php

namespace App\Models;

use App\Helpers\EncryptHelper;
use Illuminate\Database\Eloquent\Model;

class Cidade extends BaseModel
{
    protected $connection = 'pgsql';

    protected $table = 'cidades';

    public $timestamps = false;

    protected $fillable = [
        'cidade', 
        'estado_id',
        'codigo_ibge'
    ];

    public $encryptedAttributes = [
        'cidade',
        'codigo_ibge'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }

    public function setCidadeAttribute ($value) {
        if ($value) $this->attributes['cidade'] = EncryptHelper::encrypt($value);
    }

    public function getCidadeAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setCodigoIbgeAttribute ($value) {
        if ($value) $this->attributes['codigo_ibge'] = EncryptHelper::encrypt($value);
    }

    public function getCodigoIbgeAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }
}
