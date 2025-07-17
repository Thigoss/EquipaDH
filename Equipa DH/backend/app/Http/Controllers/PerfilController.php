<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repository\PerfilRepository;
use Illuminate\Http\Request;

/**
 * Controller com os endpoints referentes a perfil
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class PerfilController extends Controller
{
  
    /**
     * Repository de perfis
     *
     * @var PerfilRepository
     */
    private $repository;

    /**
     * Construtor
     *
     * @param PerfilRepository $repository
     */
    public function __construct(PerfilRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Post(
     *     tags={"perfil"},
     *     summary="Pesquisa de perfis",
     *     description="Pesquisa dos perfis registrados o sistema",
     *     path="/api/perfil",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filtro de pesquisa geral",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="ativo",
     *         in="query",
     *         description="Filtor de pesquisa por situação do perfil",
     *         required=false,
     *      ),
     *     @OA\Parameter(
     *         name="codigo",
     *         in="query",
     *         description="Filtro de pesquisa por código do perfil",
     *         required=false,
     *     ),
     *     @OA\Response(response="200", description="Pesquisa retornada com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function index(Request $request)
    {
        try {
            //Realiza a busca dos usuários
            $perfis = [];
            $busca = $request->get('nome_perfil') ?? '';
            $filtros = $request->toArray();

            if ($request->has('length')) {
                $perfis["data"] = $this->repository->search($busca, $filtros, $request->get('length'));
            } else {
                $perfis['data'] = $this->repository->search($busca, $filtros);
            }

            return ResponseHelper::responseSuccess($perfis, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     tags={"perfil"},
     *     summary="Lista de perfis ativos",
     *     description="Lista de perfis ativos registrados no sistema",
     *     path="/api/perfil/ativos",
     *     @OA\Response(response="200", description="Pesquisa retornada com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function combo()
    {
        try {
            $perfis = $this->repository->getAll();
            return ResponseHelper::responseSuccess($perfis->toArray(), "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     tags={"perfil"},
     *     summary="Consulta de dados de um perfil",
     *     description="Consulta detalhada de dados de um perfil específico.",
     *     path="/api/perfil/{id}",
     *     @OA\Response(response="200", description="Pesquisa retornada com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function show($id)
    {
        try {
            $usuario = $this->repository->find($id);

            if ($usuario) {
                return ResponseHelper::responseSuccess($usuario->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Perfil não encontrado!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function buscaPerfilHistorico($id)
    {
        try {
            $historico = $this->repository->historicoPerfil($id);

            if ($historico) {
                return ResponseHelper::responseSuccess($historico, "");
            } else {
                return ResponseHelper::responseSuccess([], "Perfil não encontrado!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
    public function buscaPerfilHistoricoPdf($perfil, $historico)
    {
        return $this->repository->pdfHistoricoPerfil($perfil, $historico);
    }

    /**
     * @OA\Post(
     *     tags={"perfil"},
     *     summary="Retorna a url de cadastro de um perfil com o autenticador",
     *     description="Retorna a url montada do autenticador para cadastro de um perfil",
     *     path="/api/perfil/store-external",
     *     @OA\Response(response="200", description="Url de cadastro retornada com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function storeFrame()
    {
        try {
            return ResponseHelper::responseSuccess(['url' => $this->repository->getStoreFrame()], "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     tags={"perfil"},
     *     summary="Retorna a url de atualização de um perfil com o autenticador",
     *     description="Retorna a url montada do autenticador para atualização de um perfil",
     *     path="/api/perfil/update-external/{id}",
     *     @OA\Response(response="200", description="Url de atualização retornada com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function updateFrame($id)
    {
        try {
            return ResponseHelper::responseSuccess(['url' => $this->repository->getUpdateFrame($id)], "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     tags={"perfil"},
     *     summary="Retorna a url de visualização de um perfil com o autenticador",
     *     description="Retorna a url montada do autenticador para visualização de um perfil",
     *     path="/api/perfil/show-external/{id}",
     *     @OA\Response(response="200", description="Url de cadastro retornada com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function showFrame($id)
    {
        try {
            return ResponseHelper::responseSuccess(['url' => $this->repository->getShowFrame($id)], "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function configurarLimite(Request $request)
    {
        try {
            $result = $this->repository->configurarLimite($request->toArray());
            if ($result) {
                return ResponseHelper::responseSuccess([], "Configuração realizada com sucesso!");
            } else {
                return ResponseHelper::responseSuccess([], "Falha ao salvar configuração!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function tipoPerfil(Request $request)
    {
        try {
            $result = $this->repository->tipoPerfil($request->toArray());
            if ($result) {
                return ResponseHelper::responseSuccess([], "Tipo de acesso vinculado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao vincular tipo de acesso!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function inativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, false)) {
                return ResponseHelper::responseSuccess([], "Perfil inativado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao inativar perfil!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function ativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, true)) {
                return ResponseHelper::responseSuccess([], "Perfil ativado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao ativar perfil!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
