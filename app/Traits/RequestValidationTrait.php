<?php

namespace App\Traits;

trait RequestValidationTrait
{
    protected function isValidID(mixed $id): bool
    {
        if ($id === null){
            return false;
        }

        $id = (string) $id;

        return ctype_digit($id) && (int) $id > 0;
    }

    protected function validateBody(array $rules, array $body): bool
    {
        $validation = \Config\Services::validation();
        $validation->setRules($rules);
        return $validation->run($body);
    }
}