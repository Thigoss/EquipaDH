<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Repository\AuthRepository;
use Illuminate\Http\Response;
use JWTException;

/**
 * Controller com os endpoints referentes a autenticação
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class AuthController extends Controller
{
  
    /**
     * Repository de autenticação
     *
     * @var AuthRepository
     */
    private $repository;
    
    /**
     * Construtor
     *
     * @param AuthRepository $repository
     */
    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     tags={"auth"},
     *     summary="Retorna a url de login com o autenticador",
     *     description="Retorna a url montada do autenticador para redirecionamento do usuário de login",
     *     path="/api/auth/login/uri",
     *     @OA\Response(response="200", description="Url retonada com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function login()
    {
        try {
            return ResponseHelper::responseSuccess(['url' => $this->repository->getLoginURI()], "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     tags={"auth"},
     *     summary="Retorna a url de logout com o autenticador",
     *     description="Retorna a url montada do autenticador para redirecionamento do usuário no logout",
     *     path="/api/auth/loout/uri",
     *     @OA\Response(response="200", description="Url retonada com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function logout()
    {
        try {
            return ResponseHelper::responseSuccess(['url' => $this->repository->getLogoutURI()], "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     tags={"auth"},
     *     summary="Realiza o login do usuário",
     *     description="Retorna o token e dados do usuário logado após o retorno do autenticador",
     *     path="/api/auth/{code}",
     *     @OA\Response(response="200", description="Usuário autenticado com sucesso."),
     *     @OA\Response(response="401", description="Usuário não autorizado."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function auth($code)
    {
        try {
            $auth = $this->repository->autenticate($code);

            if($auth['success']) {
                return ResponseHelper::responseSuccess($auth['data'], $auth['msg']);
            }else{
                return ResponseHelper::responseError([], $auth['msg']);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function switchContext($contextId)
    {
        try {
            $return = $this->repository->switchContexto($contextId);

            if ($return) {
                return ResponseHelper::responseSuccess($return, 'Contexto trocado com sucesso!');
            } else {
                return ResponseHelper::responseError([], 'Falha ao alterar o contexto!');
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
    
    public function refresh()
    {
        try {
            $token = auth()->refresh();
            return response()->json(compact('token'));
        } catch (JWTException $exception){
            report($exception);
            return response()->json(['error' => 'token_invalid'], 400);
        }
    }
}
