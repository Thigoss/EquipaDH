<?php

namespace App\Http\Validation;

use App\Helpers\ResponseHelper;
use App\Repository\InstituicaoRepository;

/**
 * Realiza as validações negociais das requisições do recurso solicitação
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class InstituicaoValidation implements IValidation {

    /**
     * Repository
     *
     * @var InstituicaoRepository
     */
    protected $instituicaoRepository;

    /**
     * Injeta os repositorys necessários para as validações
     *
     * @param InstituicaoRepository $instituicaoRepository
     */
    public function __construct(InstituicaoRepository $instituicaoRepository) {
        $this->instituicaoRepository = $instituicaoRepository;
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
        // validate same cnpj
        $cnpj = $dados['cnpj'];
        $instituicao = $this->instituicaoRepository->findByCnpj($cnpj);
        if ($instituicao && $instituicao->id != $id) {
            return ResponseHelper::responseError([], 'CNPJ já cadastrado!', false);
        }


        return ResponseHelper::responseSuccess([], '', false);
    }

}
