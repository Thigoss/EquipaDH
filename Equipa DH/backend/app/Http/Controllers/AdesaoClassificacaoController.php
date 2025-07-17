<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\AdesaoRequest;
use App\Http\Validation\AdesaoValidation;
use App\Models\Adesao;
use App\Repository\AdesaoRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller com os endpoints referentes a classificação da adesão
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class AdesaoClassificacaoController extends Controller
{
  
    /**
     * Repository de adesões
     *
     * @var AdesaoRepository
     */
    private $repository;

    /**
     * Repository de validation
     *
     * @var AdesaoValidation
     */
    private $validation;

    /**
     * Construtor
     *
     * @param AdesaoRepository $repository
     * @param AdesaoValidation $validation
     */
    public function __construct(AdesaoRepository $repository, AdesaoValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function listarClassificados(Request $request, $cronograma)
    {
        try {
            $filters = $request->all();
            $classificados = $this->repository->listarClassificados($cronograma, $filters, $request->get('length'));
            return ResponseHelper::responseSuccess($classificados->toArray(), "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function exportarClassificadosEstadualPdf(Request $request, $cronograma)
    {
        try {
            $filters = $request->all();
            $classificados = $this->repository->listarClassificados($cronograma, $filters);
            return $this->repository->exportPDFClassificados($classificados, true);
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function exportarClassificadosMunicipalPdf(Request $request, $cronograma)
    {
        try {
            $filters = $request->all();
            $classificados = $this->repository->listarClassificados($cronograma, $filters);
            return $this->repository->exportPDFClassificados($classificados);
            return ResponseHelper::responseSuccess($classificados->toArray(), "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function classificar(Request $request, $cronograma)
    {
        try {
            $data = $request->all();
            $classificar = $this->repository->classificar($cronograma, $data);
            if($classificar['success']) {
                return ResponseHelper::responseSuccess([], $classificar['msg']);
            } else {
                return ResponseHelper::responseError([], $classificar['msg']);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function enviarRecursoClassificacao(Request $request, $id)
    {
        try {
            $arquivos = json_decode($request->get('arquivos'), true);
            if ($this->repository->solicitarRecurso($id, $request->get('justificativa'), $arquivos, Adesao::TP_RECURSO_CLASSIFICACAO)) {
                return ResponseHelper::responseSuccess([], "Recurso de adesão enviado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao enviar recurso de adesão!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function visualizarRecursoClassificacao($id)
    {
        try {
            $recurso = $this->repository->visualizarRecurso($id, Adesao::TP_RECURSO_CLASSIFICACAO);
            return ResponseHelper::responseSuccess($recurso ? $recurso->toArray() : [], "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
