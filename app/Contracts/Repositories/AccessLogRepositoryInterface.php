<?php

namespace App\Contracts\Repositories;

interface AccessLogRepositoryInterface 
{
    public function findAccessHistory(array $filter, int $limit, int $offset): array;
}