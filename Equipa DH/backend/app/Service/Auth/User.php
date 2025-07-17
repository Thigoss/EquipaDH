<?php

namespace App\Service\Auth;

use App\Service\Auth\Type\User as UserType;
use App\Service\Auth\Contract\UserInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class User extends BaseService implements UserInterface 
{

    /**
     * Retorna a rota padrão de login
     *
     * @return string
     */
    public function list(UserType $user) : array 
    {
        // CODE...
        return [];
    }

    /**
     * Retorna a rota padrão de login
     *
     * @return string
     */
    public function save(UserType $user) : bool 
    {
        // Integração de login
       // try{
            $client = new Client();
            $res = $client->request('POST', $this->url_api . '/integrador/user', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->client_id.':'.$this->client_secret)
                ],
                'form_params' => [
                    'cpf' => $user->getCpf(),
                    'nome' => $user->getNome(),
                    'email' => $user->getEmail(),
                    'email_confirmation' => $user->getEmail(),
                    'ativo' => 1,
                    'perfis' => 0
                ]
            ]);
        /*}catch(GuzzleException $ex){
            report($ex);
            throw new \Exception($ex->getMessage());
        }*/

        $retorno = json_decode($res->getBody()->getContents(), true);       
        return $retorno['success'];
    }


    /**
     * Undocumented function
     *
     * @param UserType $user
     * @return User
     */
    public function get(UserType $user) : UserType
    {
        // CODE...
        return new UserType();
    }

}