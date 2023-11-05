<?php

namespace App\Service;

class CodeCreatorDecorator
{
    private CodeCreator $codeCreator;

    public function __construct(CodeCreator $codeCreator) {
        $this->codeCreator = $codeCreator;
    }

    public function createCode(string $prefix): string
    {
        $code = $this->codeCreator->createCode('prefix_arg');

        return $code . '-decorate-code';
    }
}