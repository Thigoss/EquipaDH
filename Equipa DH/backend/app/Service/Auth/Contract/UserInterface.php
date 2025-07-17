<?php 
namespace App\Service\Auth\Contract;

use App\Service\Auth\Type\User;

interface UserInterface 
{
    public function list(User $user) : array;

    public function save(User $user) : bool;

    public function get(User $user) : User;

}