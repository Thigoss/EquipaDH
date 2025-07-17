<?php

namespace App\Http\Validation;

use App\Helpers\ResponseHelper;
use App\Models\Adesao;
use App\Models\CronogramaArquivoClassificacaoEstadual;
use App\Models\CronogramaArquivoClassificacaoMunicipal;
use App\Repository\CronogramaRepository;

/**
 * Realiza as validações negociais das requisições do recurso de cronogramas
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class CronogramaValidation implements IValidation {

    /**
     * Repository
     *
     * @var CronogramaRepository
     */
    protected $cronogramaRepository;

    /**
     * Injeta os repositorys necessários para as validações
     *
     * @param CronogramaRepository $cronogramaRepository
     */
    public function __construct(CronogramaRepository $cronogramaRepository) {
        $this->cronogramaRepository = $cronogramaRepository;
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

    public function validateDelete ($id) {
        $cronograma = $this->cronogramaRepository->find($id);

        if (!$cronograma) {
            return ResponseHelper::responseError([], 'Cronograma não encontrado!', false);
        }

        $arquivosClassificacaoEstadual = CronogramaArquivoClassificacaoEstadual::where('cronograma_id', $id)->get()->toArray();
        if (sizeof($arquivosClassificacaoEstadual)) {
            return ResponseHelper::responseError([], 'Existem arquivos de classificação estadual vinculados a este cronograma!', false);
        }

        $arquivosClassificacaoMunicipal = CronogramaArquivoClassificacaoMunicipal::where('cronograma_id', $id)->get()->toArray();
        if (sizeof($arquivosClassificacaoMunicipal)) {
            return ResponseHelper::responseError([], 'Existem arquivos de classificação municipal vinculados a este cronograma!', false);
        }

        $adesoes = Adesao::where('cronograma_id', $id)->get()->toArray();
        if (sizeof($adesoes)) {
            return ResponseHelper::responseError([], 'Existem adesões vinculadas a este cronograma!', false);
        }

        return ResponseHelper::responseSuccess([], '', false);
    }

}
