<?php

namespace App\Service\Auth;

abstract class BaseService 
{

    /**
     * Url para redirect de login
     *
     * @var string
     */
    protected $url_front = null;
    
    /**
     * Url para redirect de logout
     *
     * @var string
     */
    protected $url_api = null;

    /**
     * Url de logout
     *
     * @var string
     */
    protected $url_logout = null;

    /**
     * Sigla do sistema
     *
     * @var string
     */
    protected $sigla = null;

    /**
     * Client id
     *
     * @var string
     */
    protected $client_id = null;

    /**
     * Client secret
     *
     * @var string
     */
    protected $client_secret = null;

    /**
     * Construtor
     */
    public function __construct()
    {   
        $this->url_front = config('auth-service')['url_front'];
        $this->url_api = config('auth-service')['url_api'];
        $this->url_logout = config('auth-service')['url_logout'];
        $this->sigla = config('auth-service')['sigla'];
        $this->client_id = config('auth-service')['client_id'];
        $this->client_secret = config('auth-service')['client_secret'];
    }
}