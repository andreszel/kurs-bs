<?php

namespace App\MessageHandler;

use App\Message\SendKey;
use App\Repository\UserRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendKeyHandler
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(SendKey $message)
    {
        //throw new RecoverableMessageHandlingException('test');

        $user = $this->userRepository->find($message->getUserId());

        if(!$user) {
            return;
        }

        $this->sendEmail($user->getEmail(), $this->getKey());
    }

    public function sendEmail(string $email, int $key): void
    {
        $filesystem = new Filesystem();
        
        $textMessage = $email . ', twój klucz to: ' . $key;
        
        $fileName = 'email_' . $key . '.txt';
        $filePath = 'public/emails/' . $fileName;

        $filesystem->dumpFile($filePath, $textMessage);
    }

    private function getKey()
    {
        sleep(5);
        return random_int(1000,9999);
    }
}