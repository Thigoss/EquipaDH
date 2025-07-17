<?php

namespace App\Repository;

use App\Models\Sgd;
use App\Models\Conselho;
use App\Models\Perfil;
use App\Service\Auth\Contract\AuthInterface;
use App\User;
use Illuminate\Support\Facades\Log;

/**
 * Classe de manipulação da entidade de usuário
 * 
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class AuthRepository
{

    protected $userRepository;
    protected $perfilRepository;
    protected $authService;
    protected $auditLoginRepository;
    protected $contextoRepository;

    /**
     * Construtor
     *
     * @param UserRepository $userRepository
     * @param PerfilRepository $perfilRepository
     * @param AuthInterface $authService
     * @param ContextoRepository $contextoRepository
     */
    public function __construct(UserRepository $userRepository, 
                                PerfilRepository $perfilRepository, 
                                AuthInterface $authService,
                                AuditLoginRepository $auditLoginRepository,
                                ContextoRepository $contextoRepository)
    {
        $this->userRepository = $userRepository;
        $this->perfilRepository = $perfilRepository;
        $this->authService = $authService;
        $this->auditLoginRepository = $auditLoginRepository;
        $this->contextoRepository = $contextoRepository;
    }

    /**
     * Realiza a autenticação pelo autenticador
     *
     * @param string $userCode
     * @return array
     */
    public function autenticate(string $userCode) : array
    {
        // Autentica o usuário
        try{
            $auth = $this->authService->getUserLogged($userCode);
        }catch(\Exception $ex){
            return ['success' => false, 'msg' => 'Falha na integração com autenticador'];
        }
        // Verifica o retorno do serviço
        if($auth){

            // Verifica a existência do usuário por cpf
            $user = $this->userRepository->findByCPF($auth->getCpf());
            if(!$user) {
                return ['success' => false, 'msg' => 'Usuário não registrado!'];
            }

            // Verifica se o usuário está ativo
            if(!$user->ativo) {
                return ['success' => false, 'msg' => 'Usuário inativo!'];
            }
         
            // Sincroniza os dados do perfil
            $perfisUser = $auth->getProfiles();
            $perfis = $this->perfilRepository->all();
            $perfis = $perfis->mapWithKeys(function($perfil) {
                return [$perfil->codigo => $perfil];
            });

            foreach($perfisUser as $perfil) {
                if(!isset($perfis[$perfil->getCodigo()])) {
                    $this->perfilRepository->save([
                        'codigo' => $perfil->getCodigo(),
                        'tipo' => $perfil->getTipo(),
                        'nome' => $perfil->getNome(),
                        'descricao' => $perfil->getDescricao(),
                        'tipo' => 2,
                        'ativo' => true
                    ]);
                }
            }
            // Sincroniza os dados do usuário
            $permissoes = [];
            foreach($perfisUser as $perfil) {
                foreach($perfil->getRoles() as $role) {
                    $permissoes[] = [
                        'permissao' => $role->getRole()
                    ];
                }
            }

            $perfis = [];
            foreach($user->perfis as $perfil) {
                $perfis[] = [
                    'id' => $perfil->id,
                    'perfil_id' => $perfil->perfil_id,
                    'unidade_id' => $perfil->unidade_id
                ];
            }
            
            $this->userRepository->save([
                'id' => $user->id,
                'nome' => $auth->getNome(),
                'email' => $auth->getEmail(),
                'permissoes_externas' => json_encode($permissoes),
                'perfis' => $perfis
            ]);
            
            // Salva o login na auditoria
            $this->auditLoginRepository->salvar($user);
            
            // Realiza a autenticação
            return [
                'success' => true,
                'data' => $this->auth($user->id, $permissoes),
                'msg' => 'Autenticação realizada com sucesso!'
            ];
        }else{
            return ['success' => false, 'msg' => 'Falha na comunicação com o autenticador!'];
        }
    }

    /**
     * Realiza a autenticação
     *
     * @param integer $user
     * @return array
     */
    protected function auth(int $user, $permissoes) : array
    {
        // Busca e autentica o usuário
        $user = $this->userRepository->find($user);
        $token = auth()->login($user);

        // Organiza as permissões
        $permissoesUser = $permissoes;
        $permissoes = [];

        foreach($permissoesUser as $permissao){
            $permissoes[] = $permissao['permissao'];
        }

        // Obtem os dados do perfil
        $perfil = null;
        if($user->perfis){
            $perfil = $user->perfis->count() > 0? $user->perfis[0] : null;
        }

        // Contextos
        $contextos = $this->contextoRepository->findByUser($user->id);

        $permissoes = [];
        $contextoAtual = [];
        if (sizeof($contextos) === 1) {
            $result = $this->switchContexto($contextos[0]->id);
            $contextoAtual = $result['contextoAtual'];
            $permissoes = $result['permissoes'];

            if ($result['token']) $token = $result['token'];
        }

        // Retorna os dados de autenticação
        return [
            'token' => $token, 
            'permissoes' => $permissoes, 
            'user' => $user->id, 
            'nome' => $user->nome, 
            'perfis' => $user->perfis->toArray(),
            'perfil_id' => $perfil ? $perfil->perfil->id : null,
            'perfil_nome' => $perfil ? $perfil->perfil->nome : null,
            'unidade_id' => $perfil && $perfil->unidade? $perfil->unidade->id : null,
            'unidade_nome' => $perfil && $perfil->unidade ? $perfil->unidade->nome : null,
            'instituicao_nome' => $user->instituicao ? $user->instituicao->razao_social : '',
            'contextos' => $contextos->toArray(),
            'contexto_atual' => $contextoAtual,
        ];         
    }

    public function switchContexto(int $contexto) : array
    {
        $contextoUser = $this->contextoRepository->find($contexto);
        $user = $this->userRepository->find($contextoUser->user_id);

        $permissoes = [];
        $token = null;

        // Busca as permissões do perfil no autenticador
        if ($contextoUser->perfil_id) {
            $perfil = $this->perfilRepository->getProfileByCodigo($contextoUser->perfil->codigo);
        }

        if (isset($perfil)) {
            foreach ($perfil['acoes'] as $acao) {
                $permissoes[] = $acao['funcionalidade']['sigla'].'.'.$acao['sigla'];
            }
        }

        $permissoes = json_encode($permissoes);

        if ($contextoUser) {

            $this->userRepository->save([
                'id' => $user->id,
                'permissoes_externas' => $permissoes,
                'contexto_atual_id' => $contextoUser->id,
                'automatic_transaction' => true
            ]);

            $token = $this->updateToken($contextoUser->id);
        }

        return ['permissoes' => $permissoes, 'contextoAtual' => $contextoUser, 'token' => $token];
    }

    public function updateToken ()
    {
        $user = User::find(auth()->user()->id);
        return auth()->login($user);
    }
    
    /**
     * Retorna a string de login
     *
     * @return string
     */
    public function getLoginURI() : string
    {
        return $this->authService->loginURI();
    }

    /**
     * Retorna a string de logout
     *
     * @return string
     */
    public function getLogoutURI() : string
    {
        return $this->authService->logoutURI();
    }

}
