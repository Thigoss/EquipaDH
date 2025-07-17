<?php

namespace App;

use App\Helpers\EncryptHelper;
use App\Models\Deficiencia;
use App\Models\Escolaridade;
use App\Models\Instituicao;
use App\Models\PerfilUser;
use App\Models\Raca;
use App\Models\Sexo;
use App\Models\Solicitacao;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DateTimeInterface;

/**
 * Classe de modelo de  usuários
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const TP_INTERNO = 'I';

    const TP_EXTERNO = 'E';

    public $table = 'users';

    protected $connection = 'pgsql';

    public $save_audit_transaction = true;

    protected $fillable = [
        'tipo',
        'instituicao_id',
        'cpf',
        'email',
        'nome',
        'nome_social',
        'data_nascimento',
        'sexo_id',
        'sexo_outro',
        'orientacao_sexual_id',
        'orientacao_sexual_outro',
        'contexto_atual_id',
        'raca_id',
        'escolaridade_id',
        'possui_deficiencia',
        'deficiencia_id',
        'tipo_representacao',
        'cargo',
        'telefone_funcional',
        'celular',
        'email_funcional',
        'documento_rg',
        'documento_cpf',
        'documento_ato_posse',
        'documento_ato_delegacao',
        'ativo',
        'password',
        'token',
    ];

    protected $hidden = [
        'password',
    ];

    public $encryptedAttributes = [
        'cpf',
        'email',
        'nome',
        'nome_social',
        'data_nascimento',
        'sexo_outro',
        'orientacao_sexual_outro',
        'cargo',
        'telefone_funcional',
        'celular',
        'email_funcional',
        'documento_rg',
        'documento_cpf',
        'documento_ato_posse',
        'documento_ato_delegacao',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function sexo()
    {
        return $this->belongsTo(Sexo::class, 'sexo_id', 'id');
    }

    public function raca()
    {
        return $this->belongsTo(Raca::class, 'raca_id', 'id');
    }

    public function escolaridade()
    {
        return $this->belongsTo(Escolaridade::class, 'escolaridade_id', 'id');
    }

    public function deficiencia()
    {
        return $this->belongsTo(Deficiencia::class, 'deficiencia_id', 'id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id', 'id');
    }

    public function instituicoes()
    {
        return $this->belongsToMany(Instituicao::class, 'instituicoes_usuarios', 'user_id', 'instituicao_id');
    }

    public function perfis()
    {
        return $this->hasMany(PerfilUser::class, 'user_id', 'id');
    }

    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'user_id', 'id')->orderBy('id', 'desc');
    }

    public function setCpfAttribute ($value) {
        if ($value) $this->attributes['cpf'] = EncryptHelper::encrypt($value);
    }

    public function getCpfAttribute ($value) {
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

    public function setNomeSocialAttribute ($value) {
        if ($value) $this->attributes['nome_social'] = EncryptHelper::encrypt($value);
    }

    public function getNomeSocialAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setDataNascimentoAttribute($value)
    {
        if ($value) $this->attributes['data_nascimento'] = EncryptHelper::encrypt($value);
    }
    
    public function getDataNascimentoAttribute($value)
    {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return (new \DateTime($decrypted))->format('Y-m-d');
        } else {
            return (new \DateTime($value))->format('Y-m-d');
        }
    }

    public function setSexoOutroAttribute ($value) {
        if ($value) $this->attributes['sexo_outro'] = EncryptHelper::encrypt($value);
    }

    public function getSexoOutroAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setOrientacaoSexualOutroAttribute ($value) {
        if ($value) $this->attributes['orientacao_sexual_outro'] = EncryptHelper::encrypt($value);
    }

    public function getOrientacaoSexualOutroAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setCargoAttribute ($value) {
        if ($value) $this->attributes['cargo'] = EncryptHelper::encrypt($value);
    }

    public function getCargoAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setTelefoneFuncionalAttribute ($value) {
        if ($value) $this->attributes['telefone_funcional'] = EncryptHelper::encrypt($value);
    }

    public function getTelefoneFuncionalAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setCelularAttribute ($value) {
        if ($value) $this->attributes['celular'] = EncryptHelper::encrypt($value);
    }

    public function getCelularAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setEmailFuncionalAttribute ($value) {
        if ($value) $this->attributes['email_funcional'] = EncryptHelper::encrypt($value);
    }

    public function getEmailFuncionalAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setDocumentoRgAttribute ($value) {
        if ($value) $this->attributes['documento_rg'] = EncryptHelper::encrypt($value);
    }

    public function getDocumentoRgAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setDocumentoCpfAttribute ($value) {
        if ($value) $this->attributes['documento_cpf'] = EncryptHelper::encrypt($value);
    }

    public function getDocumentoCpfAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setDocumentoAtoPosseAttribute ($value) {
        if ($value) $this->attributes['documento_ato_posse'] = EncryptHelper::encrypt($value);
    }

    public function getDocumentoAtoPosseAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setDocumentoAtoDelegacaoAttribute ($value) {
        if ($value) $this->attributes['documento_ato_delegacao'] = EncryptHelper::encrypt($value);
    }

    public function getDocumentoAtoDelegacaoAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     * @info Esse é o payload retornado no token JWT
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'perfis' => $this->perfis->toArray(),
            'contexto_atual_id' => $this->contexto_atual_id
        ];
    }
}
