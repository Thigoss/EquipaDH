<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\InstituicaoRequest;
use App\Http\Validation\InstituicaoValidation;
use App\Repository\InstituicaoRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller com os endpoints referentes a instituição
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class InstituicaoController extends Controller
{
  
    /**
     * Repository de instituiçãos
     *
     * @var InstituicaoRepository
     */
    private $repository;

    /**
     * Repository de validation
     *
     * @var InstituicaoValidation
     */
    private $validation;

    /**
     * Construtor
     *
     * @param InstituicaoRepository $repository
     * @param InstituicaoValidation $validation
     */
    public function __construct(InstituicaoRepository $repository, InstituicaoValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function index(Request $request)
    {
        try {
            //Realiza a busca dos instituiçãos
            $instituicoes = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            if ($request->has('length')) {
                $instituicoes["data"] = $this->repository->search($busca, $filtros, $request->get('length'));
            } else {
                $instituicoes['data'] = $this->repository->search($busca, $filtros);
            }

            return ResponseHelper::responseSuccess($instituicoes, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function combo(Request $request)
    {
        $data = $this->repository->all(['razao_social', 'asc'])->toArray();
        return ResponseHelper::responseSuccess($data, "");
    }

    public function show($id)
    {
        try {
            $instituicao = $this->repository->find($id);

            if ($instituicao) {
                return ResponseHelper::responseSuccess($instituicao->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Instituição não encontrada!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(InstituicaoRequest $request)
    {
        try {
            $validation = $this->validation->validate($request->toArray());

            if ($validation['success']) {
                $data = $request->toArray();
                $data['responsaveis'] = json_decode($data['responsaveis'], true);

                if ($this->repository->save($data)) {
                      return ResponseHelper::responseSuccess([],  'Instituição inserida com sucesso!');
                } else {
                    return ResponseHelper::responseError([], "Falha ao inserir instituição!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(InstituicaoRequest $request, $id)
    {
        try {
            $validation = $this->validation->validate($request->toArray(), $id);

            if ($validation['success']) {
                $data = $request->toArray();
                $data['id'] = $id;
                $data['responsaveis'] = json_decode($data['responsaveis'], true);

                if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([], "Instituição alterada com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao alterar instituição!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if ($this->repository->delete($id)) {
                return ResponseHelper::responseSuccess([], "Instituição excluída com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao excluir instituição!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function ativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, true)) {
                return ResponseHelper::responseSuccess([], "Instituição ativada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao ativar instituição!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function inativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, false)) {
                return ResponseHelper::responseSuccess([], "Instituição inativada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao inativar instituição!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
    
    public function excel(Request $request)
    {
        return $this->repository->exportExcel($request->all());
    }

    public function pdf(Request $request)
    {
        return $this->repository->exportPDF($request->all());
    }
}
