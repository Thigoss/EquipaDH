<?php

namespace App\Service\Auth\Type;

class Profile 
{   
    /**
     * Código do perfil
     *
     * @var string
     */
    protected $codigo;

    /**
     * Tipo do perfil
     *
     * @var string
     */
    protected $tipo;

    /**
     * Nome o perfil
     *
     * @var string
     */
    protected $nome;

    /**
     * Situação do perfil
     *
     * @var string
     */
    protected $situacao;

    /**
     * Descrição do perfil
     *
     * @var string
     */
    protected $descricao;

    /**
     * Lista de roles
     *
     * @var array
     */
    protected $roles;

    
    /**
     * Obtem o código
     *
     * @return  string
     */ 
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Informa o código
     *
     * @param string $codigo
     * @return self
     */ 
    public function setCodigo(string $codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Obtem o tipo
     *
     * @return string
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Informa o tipo
     *
     * @param string $tipo
     * @return self
     */ 
    public function setTipo(string $tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Obtem nome o perfil
     *
     * @return string
     */ 
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Informa o nome o perfil
     *
     * @param string $nome
     *
     * @return self
     */ 
    public function setNome(string $nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Obtem lista de roles
     *
     * @return array
     */ 
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Informa a lista de roles
     *
     * @param array $roles
     * @return self
     */ 
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Obtem a descrição
     *
     * @return string
     */ 
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Informa a descrição
     *
     * @param string $descricao
     *
     * @return self
     */ 
    public function setDescricao(string $descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Get situação do perfil
     *
     * @return  string
     */ 
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * Set situação do perfil
     *
     * @param  string  $situacao  Situação do perfil
     * @return  self
     */ 
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }
}