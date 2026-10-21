<?php 

namespace App\Traits;

trait GenerateAccessToken
{
    protected function generateAccessToken(int $bytes = 16) : string
    {
        return bin2hex(random_bytes($bytes));
    }
    
}
