<?php

namespace App\Traits;

trait RepositoryResponseTrait 
{
    protected function successResponse(mixed $data = null): array
    {
        return [
            'ok' => true,
            'data' => $data,
            'error' => null,
        ];
    }

    protected function failedResponse(mixed $error, mixed $data = null)
    {
        return [
            'ok' => false,
            'data' => $data,
            'error' => $error,
        ];
    }

    protected function failFromThrowable(\Throwable $throwable): array
    {
        return $this->failedResponse($throwable->getMessage());
    }
}