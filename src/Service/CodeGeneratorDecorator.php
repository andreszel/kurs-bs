<?php

namespace App\Service;

use App\Message\SendKey;
use Symfony\Component\Messenger\MessageBusInterface;

class CodeGeneratorDecorator
{
    public function __construct(
        private CodeGenerator $codeGenerator,
        private MessageBusInterface $bus,
        private RandomUserId $randomUserId
    ) {
    }

    public function generate(): string
    {
        $code = $this->codeGenerator->generate();

        $this->bus->dispatch(new SendKey($this->randomUserId->getRandomUserId()));
        
        return $code;
    }
}