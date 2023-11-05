<?php

namespace App\EventSubscriber;

use App\Service\TrafficService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TrafficSubscriber implements EventSubscriberInterface
{
    private TrafficService $traffic;

    public function __construct(TrafficService $traffic) {
        $this->traffic = $traffic;
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
             'kernel.request' => ['onKernelRequest', 5]
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $headers = $request->headers;

        if($headers) {
            $this->traffic->record($headers);
        }
    }
}