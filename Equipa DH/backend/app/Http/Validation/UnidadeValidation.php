<?php

namespace App\Http\Validation;

use App\Helpers\ResponseHelper;
use App\Repository\UnidadeRepository;

/**
 * Realiza as validações negociais das requisições do recurso solicitação
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class UnidadeValidation implements IValidation {

    /**
     * Repository
     *
     * @var UnidadeRepository
     */
    protected $unidadeRepository;

    /**
     * Injeta os repositorys necessários para as validações
     *
     * @param UnidadeRepository $unidadeRepository
     */
    public function __construct(UnidadeRepository $unidadeRepository) {
        $this->unidadeRepository = $unidadeRepository;
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

}
