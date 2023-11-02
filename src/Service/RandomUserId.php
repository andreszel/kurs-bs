<?php

namespace App\Service;

use App\Repository\UserRepository;

class RandomUserId
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function getRandomUserId(): int
    {
        $user = $this->userRepository->findRandomUser();

        return $user->getId();
    }
}