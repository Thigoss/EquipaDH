<?php

namespace App\Models;

use App\Helpers\EncryptHelper;
use Illuminate\Database\Eloquent\Model;

class Raca extends BaseModel
{
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'racas';
    
    protected $fillable = [
        'nome',
        'ativo'
    ];

    public $encryptedAttributes = [
        'nome'
    ];

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
}
