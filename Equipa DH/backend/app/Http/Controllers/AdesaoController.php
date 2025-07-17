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
 * Controller com os endpoints referentes a adesão
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class AdesaoController extends Controller
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

    public function index(Request $request)
    {
        try {
            //Realiza a busca das adesões
            $adesoes = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            if ($request->has('length')) {
                $adesoes["data"] = $this->repository->search($busca, $filtros, $request->get('length'));
            } else {
                $adesoes['data'] = $this->repository->search($busca, $filtros);
            }

            return ResponseHelper::responseSuccess($adesoes, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function minhasAdesoes(Request $request)
    {
        try {
            //Realiza a busca das adesões
            $adesoes = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            if ($request->has('length')) {
                $adesoes["data"] = $this->repository->search($busca, $filtros, $request->get('length'), true);
            } else {
                $adesoes['data'] = $this->repository->search($busca, $filtros, true);
            }

            return ResponseHelper::responseSuccess($adesoes, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function verificar($id)
    {
        try {
            $adesao = $this->repository->verificar($id);

            if ($adesao) {
                return ResponseHelper::responseSuccess(['verificar' => true], "");
            } else {
                return ResponseHelper::responseSuccess(['verificar' => false], "");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $adesao = $this->repository->find($id);

            if ($adesao) {
                return ResponseHelper::responseSuccess($adesao->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Adesão não encontrada");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(AdesaoRequest $request)
    {
        try {
            $validation = $this->validation->validate($request->toArray());

            if ($validation['success']) {
                $data = $request->toArray();

                if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([],  'Adesão inserida com sucesso!');
                } else {
                    return ResponseHelper::responseError([], "Falha ao inserir adesão!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(AdesaoRequest $request, $id)
    {
        try {
            $validation = $this->validation->validate($request->toArray(), $id);

            if ($validation['success']) {
                $data = $request->toArray();
                $data['id'] = $id;

                if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([], "Adesão alterada com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao alterar adesão!");
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
                return ResponseHelper::responseSuccess([], "Adesão excluída com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao excluir adesão!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function aprovar(Request $request, $id)
    {
        try {
            $adesao = Adesao::find($id);
            $tipo = $this->switchTipo($adesao->tipo);
            if ($this->repository->aprovar($id)) {
                return ResponseHelper::responseSuccess([], "$tipo aprovada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao aprovar $tipo!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function devolver(Request $request, $id)
    {
        try {
            $adesao = Adesao::find($id);
            $tipo = $this->switchTipo($adesao->tipo);
            $data = $request->all();
            if ($this->repository->devolver($id, $data)) {
                return ResponseHelper::responseSuccess([], "$tipo devolvida com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao reprovar $tipo!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function recusar(Request $request, $id)
    {
        try {
            $adesao = Adesao::find($id);
            $tipo = $this->switchTipo($adesao->tipo);
            $data = $request->all();
            if ($this->repository->recusar($id, $data)) {
                return ResponseHelper::responseSuccess([], "$tipo reprovada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao reprovar $tipo!");
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

    public function switchTipo($tipo)
    {
        switch ($tipo) {
            case Adesao::TP_ADESAO:
                return 'Adesão';
            case Adesao::TP_RECURSO_ADESAO:
                return 'Recurso de adesão';
            case Adesao::TP_RECURSO_CLASSIFICACAO:
                return 'Recurso de classificação';
            case Adesao::TP_CONVOCACAO:
                return 'Convocação';
            default:
                return 'Adesão';
        }
    }

    public function mudarInstituicao($id, $instituicao)
    {
        try {
            $validation = $this->validation->validateInstituteChange($id, $instituicao);

            if ($validation['success']) {
                if ($this->repository->changeInstitute($id, $instituicao)) {
                    return ResponseHelper::responseSuccess(msg: 'Instituição alterada com sucesso!');
                } else {
                    return ResponseHelper::responseError(msg: 'Erro ao trocar instituição!');
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError(msg: $ex->getMessage());
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

    public function instituicoesHabilitadas(Request $request)
    {
        try {
            //Realiza a busca das adesões
            $adesoes = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            if ($request->has('length')) {
                $adesoes["data"] = $this->repository->search($busca, $filtros, $request->get('length'), false, true);
            } else {
                $adesoes['data'] = $this->repository->search($busca, $filtros, false, false, true);
            }

            return ResponseHelper::responseSuccess($adesoes, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function exportHabilitadasToExcel(Request $request)
    {
        try {
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            $adesoes = $this->repository->search($busca, $filtros, false, false, true);

            return $this->repository->exportHabilitadas($adesoes->toArray());
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
