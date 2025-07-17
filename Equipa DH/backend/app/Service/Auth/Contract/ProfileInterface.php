<?php 
namespace App\Service\Auth\Contract;

use App\Service\Auth\Type\Profile;

interface ProfileInterface 
{
    public function list(Profile $profile = null) : array;

    public function get($profile) : array;

    public function attachProfile(string $user, string $profile): bool;

    public function dettachProfile(string $user, string $profile): bool;
    
    public function storeFrame(string $token) : string;

    public function updateFrame(int $id, string $token) : string;
    
    public function showFrame(int $id, string $token) : string;
}