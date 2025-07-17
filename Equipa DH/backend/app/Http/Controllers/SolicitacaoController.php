<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SolicitacaoRequest;
use App\Http\Validation\SolicitacaoValidation;
use App\Repository\SolicitacaoRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller com os endpoints referentes a solicitação
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class SolicitacaoController extends Controller
{
  
    /**
     * Repository de solicitaçãos
     *
     * @var SolicitacaoRepository
     */
    private $repository;

    /**
     * Repository de validation
     *
     * @var SolicitacaoValidation
     */
    private $validation;

    /**
     * Construtor
     *
     * @param SolicitacaoRepository $repository
     * @param SolicitacaoValidation $validation
     */
    public function __construct(SolicitacaoRepository $repository, SolicitacaoValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function index(Request $request)
    {
        try {
            //Realiza a busca dos solicitaçãos
            $solicitacoes = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            if ($request->has('length')) {
                $solicitacoes["data"] = $this->repository->search($busca, $filtros, $request->get('length'));
            } else {
                $solicitacoes['data'] = $this->repository->search($busca, $filtros);
            }

            return ResponseHelper::responseSuccess($solicitacoes, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function minhasSolicitacoes(Request $request)
    {
        try {
            //Realiza a busca dos solicitaçãos
            $solicitacoes = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            if ($request->has('length')) {
                $solicitacoes["data"] = $this->repository->minhasSolicitacoes($busca, $filtros, $request->get('length'));
            } else {
                $solicitacoes['data'] = $this->repository->minhasSolicitacoes($busca, $filtros);
            }

            return ResponseHelper::responseSuccess($solicitacoes, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $solicitacao = $this->repository->find($id);

            if ($solicitacao) {
                return ResponseHelper::responseSuccess($solicitacao->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Solicitação não encontrado");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(SolicitacaoRequest $request)
    {
        try {
            $validation = $this->validation->validate($request->toArray());

            if ($validation['success']) {
                $data = $request->toArray();

                if ($this->repository->save($data)) {
                      return ResponseHelper::responseSuccess([],  'Solicitação inserida com sucesso!');
                } else {
                    return ResponseHelper::responseError([], "Falha ao inserir solicitação!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(SolicitacaoRequest $request, $id)
    {
        try {
            $validation = $this->validation->validate($request->toArray(), $id);

            if ($validation['success']) {
                $data = $request->toArray();
                $data['id'] = $id;

                if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([], "Solicitação alterada com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao alterar solicitação!");
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
                return ResponseHelper::responseSuccess([], "Solicitação excluída com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao excluir solicitação!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function aprovar(Request $request, $id)
    {
        try {
            if ($this->repository->aprovar($id)) {
                return ResponseHelper::responseSuccess([], "Solicitação aprovada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao aprovar solicitação!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function justificar(Request $request, $id)
    {
        try {
            $data = $request->toArray();

            if ($this->repository->justificar($id, $data)) {
                return ResponseHelper::responseSuccess([], "Solicitação justificada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao justificar solicitação!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function devolver(Request $request, $id)
    {
        try {
            $data = $request->toArray();

            if ($this->repository->devolver($id, $data)) {
                return ResponseHelper::responseSuccess([], "Solicitação devolvida com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao reprovar solicitação!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function reprovar(Request $request, $id)
    {
        try {
            $data = $request->toArray();
            if ($this->repository->reprovar($id, $data)) {
                return ResponseHelper::responseSuccess([], "Solicitação reprovada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao reprovar solicitação!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function historico($id)
    {
        try {
            $historico = $this->repository->listarHistorico($id);

            return ResponseHelper::responseSuccess($historico->toArray(), "");
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
