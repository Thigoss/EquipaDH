<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\BemEquipamento;
use App\Repository\BensEquipamentosRepository;

class BensEquipamentosController extends Controller
{

    /**
     * Repository de adesões
     *
     * @var BensEquipamentosRepository
     */
    private $repository;

    /**
     * Construtor
     *
     * @param BensEquipamentosRepository $repository
     */
    public function __construct(BensEquipamentosRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {

        try {
            $bens = [];
            $filtros = $request->toArray();

            $bens['data'] = $this->repository->search($filtros);

            return ResponseHelper::responseSuccess($bens, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->toArray();

            if ($this->repository->save($data)) {
                return ResponseHelper::responseSuccess([],  'Bem inserido com sucesso!');
            } else {
                return ResponseHelper::responseError([], "Falha ao inserir Bem!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }


    public function show($id)
    {
        try {
            $bem = $this->repository->find($id);

            if ($bem) {
                return ResponseHelper::responseSuccess($bem->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Bem não encontrado");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->toArray();
            $data['id'] = $id;

            if ($this->repository->save($data)) {
                return ResponseHelper::responseSuccess([], "Bem alterado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao alterar Bem!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            if ($this->repository->delete($id)) {
                return ResponseHelper::responseSuccess([], "Bem excluído com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao excluir Bem!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
    public function ativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, true)) {
                return ResponseHelper::responseSuccess([], "Bem ativada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao ativar bem!");
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


    public function exportBensToExcel(Request $request)
    {
        try {
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            $bens = $this->repository->search($busca, $filtros, false, false, true);

            return $this->repository->exportBensToExcel($bens->toArray());
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
