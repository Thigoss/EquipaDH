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
 * Controller com os endpoints referentes a habilitação (aprovação) da adesão
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class AdesaoHabilitacaoController extends Controller
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

    public function listarHabilitados(Request $request, $cronograma)
    {
        try {
            $habilitados = $this->repository->listarHabilitados($cronograma, $request->get('length'));
            return ResponseHelper::responseSuccess($habilitados->toArray(), "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function enviarRecursoAdesao(Request $request, $id)
    {
        try {
            $arquivos = json_decode($request->get('arquivos'), true);
            if ($this->repository->solicitarRecurso($id, $request->get('justificativa'), $arquivos, Adesao::TP_RECURSO_ADESAO)) {
                return ResponseHelper::responseSuccess([], "Recurso de adesão enviado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao enviar recurso de adesão!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function visualizarRecursoAdesao($id)
    {
        try {
            $recurso = $this->repository->visualizarRecurso($id, Adesao::TP_RECURSO_ADESAO);
            return ResponseHelper::responseSuccess($recurso ? $recurso->toArray() : [], "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
