<?php

namespace App\Http\Validation;

use App\Helpers\ResponseHelper;
use App\Models\Instituicao;
use App\Repository\SolicitacaoRepository;

/**
 * Realiza as validações negociais das requisições do recurso solicitação
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class SolicitacaoValidation implements IValidation {

    /**
     * Repository
     *
     * @var SolicitacaoRepository
     */
    protected $solicitacaoRepository;

    /**
     * Injeta os repositorys necessários para as validações
     *
     * @param SolicitacaoRepository $solicitacaoRepository
     */
    public function __construct(SolicitacaoRepository $solicitacaoRepository) {
        $this->solicitacaoRepository = $solicitacaoRepository;
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
    
        //Valida se o cpf não está sendo utilizado por outra solicitação
        if ($this->solicitacaoRepository->findByCPF($dados['cpf'], $id)) {
            return ResponseHelper::responseError([], 'O cpf informado já está sendo utilizado por outra solicitação!', false);
        }

        if (isset($data['instituicoes']) && isset($data['instituicoes'][0])) {
            $instituicao = Instituicao::where('cnpj', ($data['instituicoes'][0]['cnpj']))->first();

            if ($instituicao && !$instituicao->ativo) {
                return ResponseHelper::responseError([], 'Credenciamento da instituição encontra-se Inativo!', false);
            }
        }

        if (isset($data['instituicao_id']) && !empty($data['instituicao_id'])) {
            $instituicao = Instituicao::find($data['instituicao_id']);

            if ($instituicao && !$instituicao->ativo) {
                return ResponseHelper::responseError([], 'Credenciamento da instituição encontra-se Inativo!', false);
            }
        }
        
        return ResponseHelper::responseSuccess([], '', false);
    }

}
