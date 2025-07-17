<?php

namespace App\Service\Auth;

use App\Service\Auth\Type\Profile as ProfileType;
use App\Service\Auth\Contract\ProfileInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Profile extends BaseService implements ProfileInterface 
{
  
    /**
     * Lista os perfis
     *
     * @param Profile $profile
     * @return array
     */
    public function list(ProfileType $profile = null) : array
    {
        try{
            $client = new Client();
            $res = $client->request('GET', $this->url_api . '/integrador/perfis', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->client_id.':'.$this->client_secret)
                ]
            ]);
        }catch(GuzzleException $ex){
            report($ex);
            throw new \Exception($ex->getMessage());
        }

        $retorno = json_decode($res->getBody()->getContents(), true);       
        
        $perfis = [];
        foreach($retorno['data']['data'] as $perfil){
            $profileType = new ProfileType();
            $profileType->setCodigo($perfil['id'])
                    ->setNome($perfil['nome'])
                    ->setSituacao($perfil['ativo'])
                    ->setDescricao('');

            $perfis[] = $profileType;
        }

        return $perfis;
    }

    /**
     * Obtem os dados de um perfil específico
     *
     * @param Profile $profile
     * @return ProfileType
     */
    public function get($profile) : array
    {
        try{
            $client = new Client();
            $res = $client->request('GET', $this->url_api . '/integrador/' . $profile . '/perfil', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->client_id.':'.$this->client_secret)
                ]
            ]);
        }catch(GuzzleException $ex){
            report($ex);
            throw new \Exception($ex->getMessage());
        }

        $retorno = json_decode($res->getBody()->getContents(), true);  
        return $retorno['data'];
    }

    /**
     * Atribui os perfis
     *
     * @param string $user
     * @param string $profile
     * @return boolean
     */
    public function attachProfile(string $user, string $profile): bool
    {
        try{
            $client = new Client();
            $res = $client->request('PUT', $this->url_api . '/integrador/user/perfil/' .$user . '/' . $profile, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->client_id.':'.$this->client_secret)
                ]
            ]);
        }catch(GuzzleException $ex){
            report($ex);
            throw new \Exception($ex->getMessage());
        }

        $retorno = json_decode($res->getBody()->getContents(), true);  
        return $retorno['success'];
    }


    /**
     * Retira um perfil para um usuário
     * 
     * @param string $user
     * @param integer $profile
     * @return boolean
     */
    public function dettachProfile(string $user, string $profile) : bool
    {
        try{
            $client = new Client();
            $res = $client->request('DELETE', $this->url_api . '/integrador/user/perfil/' .$user . '/' . $profile, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->client_id.':'.$this->client_secret)
                ]
            ]);
        }catch(GuzzleException $ex){
            report($ex);
            throw new \Exception($ex->getMessage());
        }

        $retorno = json_decode($res->getBody()->getContents(), true);  
        return $retorno['success'];
    }

    /**
     * Retorna a url do frame de cadastro de perfil
     *
     * @return string
     */
    public function storeFrame(string $token) : string
    {
        if(auth()->user()){
            return $this->url_front . '/integracao/perfil/cadastrar/' . $token . '/' . auth()->user()->id;
        }else{
            return $this->url_front . '/integracao/perfil/cadastrar/' . $token;
        }
    }

    /**
     * Retorna a url do frame de atualização de dados do perfil
     *
     * @param integer $id
     * @return string
     */
    public function updateFrame(int $id, string $token) : string
    {
        if(auth()->user()){
            return $this->url_front . '/integracao/perfil/' . $id . '/editar/' . $token . '/' . auth()->user()->id;
        }else{
            return $this->url_front . '/integracao/perfil/' . $id . '/editar/' . $token;
        }
    }
    
    /**
     * Retorna a url do frame para visualização dos dados do perfil
     *
     * @param integer $id
     * @return string
     */
    public function showFrame(int $id, string $token) : string
    {
        if(auth()->user()){
            return $this->url_front . '/integracao/perfil/' . $id . '/visualizar/' . $token . '/' . auth()->user()->id;;
        }else{
            return $this->url_front . '/integracao/perfil/' . $id . '/visualizar/' . $token;
        }
    }

}