<?php

namespace App\Service\Auth\Type;

class Role 
{
    
    /**
     * Especificação da role
     *
     * @var string
     */
    protected $role;

    /**
     * Obtem especificação da role
     *
     * @return string
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Informa especificação da role
     *
     * @param string $role
     * @return self
     */ 
    public function setRole(string $role)
    {
        $this->role = $role;
        return $this;
    }
}