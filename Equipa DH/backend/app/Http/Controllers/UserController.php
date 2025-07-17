<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Http\Validation\UserValidation;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller com os endpoints referentes a usuário
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class UserController extends Controller
{
  
    /**
     * Repository de usuários
     *
     * @var UserRepository
     */
    private $repository;

    /**
     * Repository de validation
     *
     * @var UserValidation
     */
    private $validation;

    /**
     * Construtor
     *
     * @param UserRepository $repository
     * @param UserValidation $validation
     */
    public function __construct(UserRepository $repository, UserValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function index(Request $request)
    {
        try {
            //Realiza a busca dos usuários
            $usuarios = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();

            if ($request->has('length')) {
                $usuarios["data"] = $this->repository->search($busca, $filtros, $request->get('length'));
            } else {
                $usuarios['data'] = $this->repository->search($busca, $filtros);
            }

            return ResponseHelper::responseSuccess($usuarios, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $usuario = $this->repository->find($id);

            if ($usuario) {
                return ResponseHelper::responseSuccess($usuario->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Usuário não encontrado");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function getByUnidade($id)
    {
        try {
            $usuarios = $this->repository->getByUnidade($id);

            if ($usuarios) {
                return ResponseHelper::responseSuccess($usuarios->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Usuários não encontrados");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function getByInstituicao($id)
    {
        try {
            $usuarios = $this->repository->getByInstituicao($id);

            if ($usuarios) {
                return ResponseHelper::responseSuccess($usuarios->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Usuários não encontrados");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(UserRequest $request)
    {
        try {
            $validation = $this->validation->validate($request->toArray());

            if ($validation['success']) {
                $data = $request->toArray();

                if ($this->repository->save($data)) {
                      return ResponseHelper::responseSuccess([],  'Usuário inserido com sucesso!');
                } else {
                    return ResponseHelper::responseError([], "Falha ao inserir usuário!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(UserRequest $request, $id)
    {
        try {
            $validation = $this->validation->validate($request->toArray(), $id);

            if ($validation['success']) {
                $data = $request->toArray();
                $data['id'] = $id;

                if(isset($data['perfis'])){
                    $data['perfis'] = json_decode($data['perfis'], true);
                }

                if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([], "Usuário alterado com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao alterar usuário!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function atualizarInstituicaoUser(Request $request, $id)
    {
        try {
            if ($this->repository->atualizarInstituicaoUser($id, $request->get('instituicao_id'))) {
                return ResponseHelper::responseSuccess([], "Instituição alterada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao alterar instituição!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function vincularUser(Request $request)
    {
        try {
            if ($this->repository->vincularUser($request->toArray())) {
                return ResponseHelper::responseSuccess([], "Usuário vinculado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao vincular usuário!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function alterarVinculo($id)
    {
        try {
            $result = $this->repository->alterarVinculo($id);
            if ($result['success']) {
                return ResponseHelper::responseSuccess([], $result['msg']);
            } else {
                return ResponseHelper::responseError([], $result['msg']);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if ($this->repository->delete($id)) {
                return ResponseHelper::responseSuccess([], "Usuário excluído com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao excluir usuário! Verifique se o mesmo não possui solicitações e/ou adesões vínculadas.");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function inativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, false)) {
                return ResponseHelper::responseSuccess([], "Usuário suspenso com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao suspender usuário!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function ativar($id)
    {
        try {
            if ($this->repository->changeActivate($id, true)) {
                return ResponseHelper::responseSuccess([], "Usuário ativado com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao ativar usuário!");
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

    public function historicoPdf(Request $request)
    {
        return $this->repository->historicoPdf($request->all());
    }
}
