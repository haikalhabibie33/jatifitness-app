<?php

namespace App\Contracts\Repositories;

interface RememberTokenRepositoryInterface 
{
    public function create(array $data): array;
}