<?php

namespace App\Service\Auth;

use App\Service\Auth\Type\Profile;
use App\Service\Auth\Type\Role;
use App\Service\Auth\Type\User;
use App\Service\Auth\Contract\AuthInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Auth extends BaseService implements AuthInterface 
{
    
    /**
     * Retorna a rota padrão de login
     *
     * @return string
     */
    public function loginURI() : string 
    {
        return $this->url_front . '/login-externo/' . $this->sigla;
    }

    /**
     * Retorna a rota padrão de logout
     *
     * @return string
     */
    public function logoutURI() : string 
    {
        return $this->url_logout;
    }

    /**
     * Obtem os dados do usuário logado
     *
     * @param string $code
     * @return User
     */
    public function getUserLogged(string $code) : User
    {
        // Integração de login
        try{
            $client = new Client();
            $res = $client->request('GET', $this->url_api . '/integrador/auth/user/' . $code);
        }catch(GuzzleException $ex){
            report($ex);
            throw new \Exception($ex->getMessage());
        }
        $retorno = json_decode($res->getBody()->getContents(), true);       
        $data = $retorno['data'];

        // Informa os perfis
        $profiles = [];

        if($data['permissoes']){
            foreach($data['permissoes'][0] as $perfil)
            {            
                // Informa as roles permitidas para o perfil
                $roles = [];
                foreach($perfil['permissoes'] as $permissao)
                {
                    $role = new Role();
                    $role->setRole($permissao);
                    $roles[] = $role;
                }
    
                // Informa os dados do perfil do usuário
                $profile = new Profile();
                $profile->setNome($perfil['nome'])
                        ->setCodigo($perfil['id'])
                        ->setRoles($roles);
    
                $profiles[] = $profile;
            }
        }
       

        // Monta os dados do usuário
        $user = new User();
        $user->setCpf(str_replace(['.', '-'], ['', ''], $data['cpf']))
             ->setNome($data['nome'])
             ->setEmail($data['email'] ? $data['email'] : '')
             ->setProfiles($profiles);

        return $user;
    } 

    /**
     * Retorna o token
     *
     * @return string
     */
    public function getToken() : string
    {
        // Integração de login
        try{
            $client = new Client();
            $res = $client->request('POST', $this->url_api . '/integrador/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->client_id.':'.$this->client_secret)
                ]
            ]);
        }catch(GuzzleException $ex){
            report($ex);
            throw new \Exception($ex->getMessage());
        }

        $retorno = json_decode($res->getBody()->getContents(), true);       
        return $retorno['data']['token'];
    }
}