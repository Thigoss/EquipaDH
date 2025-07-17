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
 * Controller com os endpoints referentes a convocação da adesão
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class AdesaoConvocacaoController extends Controller
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

    public function listarConvocados(Request $request, $cronograma)
    {
        try {
            $convocados = $this->repository->listarConvocados($cronograma, $request->get('length'));
            return ResponseHelper::responseSuccess($convocados->toArray(), "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function convocar($id)
    {
        try {
            $this->repository->convocar($id);
            return ResponseHelper::responseSuccess([], "Convocação realizada com sucesso");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function enviarTermo(Request $request, $id)
    {
        try {
            $this->repository->enviarTermo($id, $request->get('arquivo'));
            return ResponseHelper::responseSuccess([], "Termo enviado com sucesso");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
