<?php

namespace App\Http\Validation;

use App\Helpers\ResponseHelper;
use App\Models\Adesao;
use App\Repository\AdesaoRepository;
use App\Repository\ContextoRepository;
use Illuminate\Support\Facades\Log;

/**
 * Realiza as validações negociais das requisições do recurso de adesões
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class AdesaoValidation implements IValidation {

    /**
     * Repository
     *
     * @var AdesaoRepository
     */
    protected $adesaoRepository;

    /**
     * Injeta os repositorys necessários para as validações
     *
     * @param AdesaoRepository $adesaoRepository
     */
    public function __construct(AdesaoRepository $adesaoRepository) {
        $this->adesaoRepository = $adesaoRepository;
    }

    /**
     * Validação do salvamento
     *
     * @param array $dados
     * @param type $id
     * @param type $validateId
     * @return type
     */
    public function validate(array $dados, $id = 0) {
    
        return ResponseHelper::responseSuccess([], '', false);
    }

    public function validateInstituteChange ($id, $instituicao)
    {
        $contexto = ContextoRepository::contextoLogado();

        if ($contexto['contexto']->perfil->tipo != 1) {
            return ResponseHelper::responseError(msg: 'Você não tem permissão para esta ação!', json: false);
        }

        $adesao = Adesao::find($id);
        if (!$adesao) {
            return ResponseHelper::responseError(msg: 'Adesão não encontrada!', json: false);
        }

        $adesaoInstituicaoExistente = Adesao::where('cronograma_id', $adesao->cronograma_id)->where('instituicao_id', $instituicao)->first();
        if ($adesaoInstituicaoExistente) {
            return ResponseHelper::responseError(msg: 'Instituição já possui uma adesão no cronograma!', json: false);
        }

        return ResponseHelper::responseSuccess([], '', false);
    }

}
