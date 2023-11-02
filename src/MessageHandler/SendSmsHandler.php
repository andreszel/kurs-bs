<?php

namespace App\MessageHandler;

use App\Message\SendSms;
use App\Repository\UserRepository;
use DateTime;
use DateTimeZone;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;

#[AsMessageHandler]
class SendSmsHandler
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(SendSms $message)
    {
        $user = $this->userRepository->find($message->getUserId());

        if(!$user){
            return;
        }

        $phone = $user->getPhone();

        if($phone) {
            if($message->getRandomDigit() % 2 == 0) {
                throw new RecoverableMessageHandlingException('Even random int, we have to ship later :)');
            }
            $this->sendSms($phone, $this->getKey());
        }else{
            throw new UnrecoverableMessageHandlingException('Empty phone');
        }
    }

    public function sendSms(string $phone, int $key): void
    {
        $filesystem = new Filesystem();
        $currentDateTime = new DateTime('now', new DateTimeZone('Europe/Warsaw'));

        $textSms = $currentDateTime->format("Y-m-d H:i:s") . "\n" . $phone . ', twój kod: ' . $key . "\n";

        $fileName = 'sms_' . substr($phone, 3,9) . '.txt';
        $filePath = 'public/smses/' . $fileName;

        //$filesystem->dumpFile($filePath, $textSms);
        $filesystem->appendToFile($filePath, $textSms, true);
    }

    private function getKey()
    {
        sleep(5);
        return random_int(1000,9999);
    }
}