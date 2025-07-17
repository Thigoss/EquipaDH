<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProgramaRequest;
use App\Http\Validation\ProgramaValidation;
use App\Repository\ProgramaRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller com os endpoints referentes a política pública
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class ProgramaController extends Controller
{
  
    /**
     * Repository de política públicas
     *
     * @var ProgramaRepository
     */
    private $repository;

    /**
     * Repository de validation
     *
     * @var ProgramaValidation
     */
    private $validation;

    /**
     * Construtor
     *
     * @param ProgramaRepository $repository
     * @param ProgramaValidation $validation
     */
    public function __construct(ProgramaRepository $repository, ProgramaValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function index(Request $request)
    {
        try {
            //Realiza a busca dos política públicas
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

    public function listarAtivos(Request $request)
    {
        try {
            $programas = $this->repository->getProgramasAtivos();
            return ResponseHelper::responseSuccess($programas->toArray(), "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function visualizarAtivo($id)
    {
        try {
            $programa = $this->repository->findAtivo($id);

            if ($programa) {
                return ResponseHelper::responseSuccess($programa->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Política Pública não encontrada!");
            }
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
            $programa = $this->repository->find($id);

            if ($programa) {
                return ResponseHelper::responseSuccess($programa->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Política Pública não encontrada!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(ProgramaRequest $request)
    {
        try {
            $validation = $this->validation->validate($request->toArray());

            if ($validation['success']) {
                $data = $request->toArray();

                if ($this->repository->save($data)) {
                      return ResponseHelper::responseSuccess([],  'Política Pública inserida com sucesso!');
                } else {
                    return ResponseHelper::responseError([], "Falha ao inserir política pública!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(ProgramaRequest $request, $id)
    {
        try {
            $validation = $this->validation->validate($request->toArray(), $id);

            if ($validation['success']) {
                $data = $request->toArray();
                $data['id'] = $id;
                
                if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([], "Política Pública alterada com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao alterar política pública!");
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
                return ResponseHelper::responseSuccess([], "Política Pública excluída com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao excluir política pública! Verifique se a mesma não possui cronogramas vínculados.");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function ativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, true)) {
                return ResponseHelper::responseSuccess([], "Política Pública ativada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao ativar política pública!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function inativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, false)) {
                return ResponseHelper::responseSuccess([], "Política Pública inativada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao inativar política pública!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function getHistorico ($id)
    {
        try {
            $historico = $this->repository->getHistorico($id);
            return ResponseHelper::responseSuccess($historico, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
