<?php

namespace App\Http\Validation;

use App\Helpers\ResponseHelper;
use App\Helpers\UtilHelper;
use App\Repository\PerfilRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;

/**
 * Realiza as validações negociais das requisições do recurso Usuário
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class UserValidation implements IValidation {

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $usuarioRepository;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $util;

    /**
     * Injeta os repositorys necessários para as validações
     *
     * @param IRepository $usuarioRepository
     */
    public function __construct(UserRepository $usuarioRepository, UtilHelper $util) {
        $this->usuarioRepository = $usuarioRepository;
        $this->util = $util;
    }

    /**
     * Validação do salvamento de um usuário
     *
     * @param array $dados
     * @param type $id
     * @param type $validateId
     * @return type
     */
    public function validate(array $dados, $id = 0) {

        //Valida se o cpf não está sendo utilizado por outro usuário
        if ($this->usuarioRepository->findByCPF(preg_replace('/\D/', '', trim($dados['cpf'])), $id)) {
            return ResponseHelper::responseError([], 'O cpf informado já está sendo utilizado por outro usuário!', false);
        }

        //Valida se o e-mail não está sendo utilizado por outro usuário
        /* if ($this->usuarioRepository->findByEmail($dados['email'], $id)) {
            return ResponseHelper::responseError([], 'O e-mail informado já está sendo utilizado por outro usuário!', false);
        }*/

        //Valida o id do usuário
        if ($id) {
            $usuario = $this->usuarioRepository->find($id);

            if (!$usuario) {
                return ResponseHelper::responseError([], 'O usuário informado não está cadastrado', false);
            }
        }

        return ResponseHelper::responseSuccess([], '', false);
    }

}
