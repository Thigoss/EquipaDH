<?php 
namespace App\Service\Auth\Contract;

use App\Service\Auth\Type\User;

interface AuthInterface 
{
    public function loginURI() : string;

    public function logoutURI() : string;
    
    public function getUserLogged(string $code) : User; 

    public function getToken() : string;
}