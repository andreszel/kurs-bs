<?php

namespace App\Service;

class CodeCreator
{
    public function createCode(string $prefix): string
    {
        return $prefix . '-' . rand(1000,9000);
    }
}