<?php

namespace App\EventSubscriber\Api\Authentication;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class LoginFailure implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [ResponseEvent::class => 'onResponseEvent'];
    }

    public function onResponseEvent(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $route = $request->attributes->get('_route');
        $response = $event->getResponse();
        $statusCode = $response->getStatusCode();

        if (preg_match('/^(?:api_|_api_|api-|api:|)/', $route)) {
            if (Response::HTTP_UNAUTHORIZED === $statusCode) {
                $response->setContent('Missing credentials!');
            }

            if (Response::HTTP_FORBIDDEN === $statusCode) {
                $response->setContent("You don't have access to this resource!");
            }
        }
    }
}
