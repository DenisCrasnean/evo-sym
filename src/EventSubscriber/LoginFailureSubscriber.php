<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class LoginFailureSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $analyticsLogger;

    public function __construct(LoggerInterface $analyticsLogger)
    {
        $this->analyticsLogger = $analyticsLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginFailureEvent::class => 'onLoginFailure'];
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $this->analyticsLogger->notice(
            'User login failure!',
            [
                'event' => 'LoginFailure',
                'firewall' => $event->getFirewallName(),
            ]
        );
    }
}
