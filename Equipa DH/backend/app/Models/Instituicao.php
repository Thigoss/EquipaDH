<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de instituições
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class Instituicao extends BaseModel
{
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'instituicoes';
 
    protected $fillable = [
        'razao_social',
        'cnpj',
        'email',
        'esfera',
        'telefone',
        'estado_id',
        'cidade_id',
        'cep',
        'endereco',
        'bairro',
        'numero',
        'complemento',
        'ativo',
        'autoridade_rg',
        'autoridade_cpf',
        'autoridade_ato_posse',
        'autoridade_ato_delegacao'
    ];

    public function representantes()
    {
        return $this->hasMany(InstituicaoRepresentante::class, 'instituicao_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'instituicoes_usuarios', 'instituicao_id', 'user_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class, 'cidade_id', 'id');
    }
}
