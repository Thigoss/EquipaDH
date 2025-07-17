<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CronogramaRequest;
use App\Http\Validation\CronogramaValidation;
use App\Repository\CronogramaRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller com os endpoints referentes a cronograma
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class CronogramaController extends Controller
{
  
    /**
     * Repository de cronogramas
     *
     * @var CronogramaRepository
     */
    private $repository;

    /**
     * Repository de validation
     *
     * @var CronogramaValidation
     */
    private $validation;

    /**
     * Construtor
     *
     * @param CronogramaRepository $repository
     * @param CronogramaValidation $validation
     */
    public function __construct(CronogramaRepository $repository, CronogramaValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function index(Request $request)
    {
        try {
            //Realiza a busca dos cronogramas
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
        $data = $this->repository->all(['numero', 'desc'])->toArray();
        return ResponseHelper::responseSuccess($data, "");
    }

    public function show($id)
    {
        try {
            $cronograma = $this->repository->find($id);

            if ($cronograma) {
                return ResponseHelper::responseSuccess($cronograma->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Cronograma não encontrado!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(CronogramaRequest $request)
    {
        try {
            $validation = $this->validation->validate($request->toArray());

            if ($validation['success']) {
                $data = $request->toArray();

                if ($this->repository->save($data)) {
                      return ResponseHelper::responseSuccess([],  'Cronograma inserido com sucesso!');
                } else {
                    return ResponseHelper::responseError([], "Falha ao inserir cronograma!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(CronogramaRequest $request, $id)
    {
        try {
            $validation = $this->validation->validate($request->toArray(), $id);

            if ($validation['success']) {
                $data = $request->toArray();
                $data['id'] = $id;

                if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([], "Cronograma alterado com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao alterar cronograma!");
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
            $validation = $this->validation->validateDelete($id);

            if ($validation['success']) {
                if ($this->repository->delete($id)) {
                    return ResponseHelper::responseSuccess([], "Cronograma excluído com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao excluir cronograma!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function cancelar(Request $request)
    {
        try {
            if ($this->repository->cancelar($request->all())) {
                return ResponseHelper::responseSuccess([], "Cronograma cancelado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao cancelar cronograma!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
