<?php

namespace App\Models;

use App\Helpers\EncryptHelper;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de solicitação
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class Solicitacao extends BaseModel
{
    const ST_APROVADO = 'AP';

    const ST_REPROVADO = 'RE';

    const ST_PENDENTE = 'PE';

    const ST_DEVOLVIDO = 'DE';
    
    protected $connection = 'pgsql';

    public $save_audit_transaction = true;
    public $table = 'solicitacoes';
 
    protected $fillable = [
        'user_id',
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
        'raca_id',
        'raca_outro',
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
        'autoridade_rg',
        'autoridade_cpf',
        'autoridade_ato_posse',
        'autoridade_ato_delegacao',
        'situacao',
        'termo_aceite'
    ];

    public $encryptedAttributes = [
        'cpf',
        'email',
        'nome',
        'nome_social',
        'data_nascimento',
        'raca_outro',
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
        'autoridade_rg',
        'autoridade_cpf',
        'autoridade_ato_posse',
        'autoridade_ato_delegacao'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id', 'id');
    }

    public function sexo()
    {
        return $this->belongsTo(Sexo::class, 'sexo_id', 'id');
    }

    public function orientacaoSexual()
    {
        return $this->belongsTo(OrientacaoSexual::class, 'orientacao_sexual_id', 'id');
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

    public function historicos()
    {
        return $this->hasMany(SolicitacaoHistorico::class, 'solicitacao_id', 'id');
    }

    public function solicitacaoInstituicao()
    {
        return $this->hasMany(SolicitacaoInstituicao::class, 'solicitacao_id', 'id');
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

    public function setRacaOutroAttribute ($value) {
        if ($value) $this->attributes['raca_outro'] = EncryptHelper::encrypt($value);
    }

    public function getRacaOutroAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
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

    public function setAutoridadeRgAttribute ($value) {
        if ($value) $this->attributes['autoridade_rg'] = EncryptHelper::encrypt($value);
    }

    public function getAutoridadeRgAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setAutoridadeCpfAttribute ($value) {
        if ($value) $this->attributes['autoridade_cpf'] = EncryptHelper::encrypt($value);
    }

    public function getAutoridadeCpfAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setAutoridadeAtoPosseAttribute ($value) {
        if ($value) $this->attributes['autoridade_ato_posse'] = EncryptHelper::encrypt($value);
    }

    public function getAutoridadeAtoPosseAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function setAutoridadeAtoDelegacaoAttribute ($value) {
        if ($value) $this->attributes['autoridade_ato_delegacao'] = EncryptHelper::encrypt($value);
    }

    public function getAutoridadeAtoDelegacaoAttribute ($value) {
        $decrypted = EncryptHelper::decrypt($value);
        if ($decrypted) {
            return $decrypted;
        } else {
            return $value;
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return (new \DateTime($value))->format('d/m/Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return (new \DateTime($value))->format('d/m/Y H:i');
    }
}
