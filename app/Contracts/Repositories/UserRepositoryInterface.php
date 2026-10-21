<?php

namespace App\Contracts\Repositories;

interface UserRepositoryInterface 
{
    public function login(string $username): array;
    
}