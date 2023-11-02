<?php

namespace App\Message;

class SendSms
{
    public function __construct(
        private int $userId
    ) {
    }
    
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRandomDigit(): int
    {
        return random_int(1,9);
    }
}