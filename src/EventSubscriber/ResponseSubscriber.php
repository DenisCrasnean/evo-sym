<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseSubscriber implements EventSubscriberInterface
{
    private string $apiVersion;

    public function __construct(string $apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    public static function getSubscribedEvents(): array
    {
        return [ResponseEvent::class => 'onKernelRequest'];
    }

    public function onKernelRequest(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $route = '';

        if (null !== $request->attributes->get('_route')) {
            $route = substr($request->attributes->get('_route'), 0, 3);
        }

        if ('api' === $route) {
            $response = $event->getResponse();
            $response->headers->set('X-API-VERSION', $this->apiVersion);
        }
    }
}
