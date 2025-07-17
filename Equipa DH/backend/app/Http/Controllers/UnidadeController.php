<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UnidadeRequest;
use App\Http\Validation\UnidadeValidation;
use App\Repository\UnidadeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller com os endpoints referentes a unidade
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class UnidadeController extends Controller
{
  
    /**
     * Repository de unidades
     *
     * @var UnidadeRepository
     */
    private $repository;

    /**
     * Repository de validation
     *
     * @var UnidadeValidation
     */
    private $validation;

    /**
     * Construtor
     *
     * @param UnidadeRepository $repository
     * @param UnidadeValidation $validation
     */
    public function __construct(UnidadeRepository $repository, UnidadeValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function index(Request $request)
    {
        try {
            //Realiza a busca dos unidades
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
        $data = $this->repository->all(['nome', 'asc'])->toArray();
        return ResponseHelper::responseSuccess($data, "");
    }

    public function show($id)
    {
        try {
            $unidade = $this->repository->find($id);

            if ($unidade) {
                return ResponseHelper::responseSuccess($unidade->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Unidade não encontrada!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(UnidadeRequest $request)
    {
        try {
            $validation = $this->validation->validate($request->toArray());

            if ($validation['success']) {
                $data = $request->toArray();
                $data['responsaveis'] = json_decode($data['responsaveis'], true);

                if ($this->repository->save($data)) {
                      return ResponseHelper::responseSuccess([],  'Unidade inserida com sucesso!');
                } else {
                    return ResponseHelper::responseError([], "Falha ao inserir unidade!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(UnidadeRequest $request, $id)
    {
        try {
            $validation = $this->validation->validate($request->toArray(), $id);

            if ($validation['success']) {
                $data = $request->toArray();
                $data['id'] = $id;
                $data['responsaveis'] = json_decode($data['responsaveis'], true);

                if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([], "Unidade alterada com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao alterar unidade!");
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
                return ResponseHelper::responseSuccess([], "Unidade excluída com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao excluir unidade! Verifique se a mesma não possui usuários e políticas públicas vinculadas.");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function ativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, true)) {
                return ResponseHelper::responseSuccess([], "Unidade ativada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao ativar unidade!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function inativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, false)) {
                return ResponseHelper::responseSuccess([], "Unidade inativada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao inativar unidade!");
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
