<?php

namespace App\Service\Auth\Type;

class User 
{
    
    /**
     * CPF do usuário
     *
     * @var string
     */
    protected $cpf;

    /**
     * Nome do usuário
     *
     * @var string
     */
    protected $nome;

    /**
     * E-mail do usuário
     *
     * @var string
     */
    protected $email;
    
    /**
     * Lista de perfis do usuário
     *
     * @var Profile
     */
    protected $profiles;

    /**
     * Obtem o valor de cpf
     *
     * @return string
     */ 
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Informa o valor de cpf
     *
     * @param string $cpf
     * @return self
     */ 
    public function setCpf(string $cpf)
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * Obtem o nome do usuário
     *
     * @return string
     */ 
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Informa o nome do usuário
     *
     * @param string $nomeo
     * @return self
     */ 
    public function setNome(string $nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Obtem o valor de email
     *
     * @return string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Informa o valor de email
     *
     * @param string $email
     * @return self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Obtem a lista de perfis do usuário
     *
     * @return array
     */ 
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * Informa a lista de perfis do usuário
     *
     * @param array $profiles
     * @return self
     */ 
    public function setProfiles(array $profiles)
    {
        $this->profiles = $profiles;
        return $this;
    }

}