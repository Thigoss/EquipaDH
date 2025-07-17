<?php

namespace App\Http\Validation;

use App\Helpers\ResponseHelper;
use App\Repository\ProgramaRepository;

/**
 * Realiza as validações negociais das requisições do recurso solicitação
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class ProgramaValidation implements IValidation {

    /**
     * Repository
     *
     * @var ProgramaRepository
     */
    protected $programaRepository;

    /**
     * Injeta os repositorys necessários para as validações
     *
     * @param ProgramaRepository $programaRepository
     */
    public function __construct(ProgramaRepository $programaRepository) {
        $this->programaRepository = $programaRepository;
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
