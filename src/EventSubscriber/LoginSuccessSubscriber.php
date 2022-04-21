<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $analyticsLogger;

    public function __construct(LoggerInterface $analyticsLogger)
    {
        $this->analyticsLogger = $analyticsLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSuccess'];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $this->analyticsLogger->info(
                'User authenticated successfully!',
                [
                    'event' => 'LoginSuccess',
                    'firewall' => $event->getFirewallName(),
                    'user' => $event->getUser()->getUserIdentifier(),
                    'role' => $event->getUser()->getRoles(),
                ]
            );
    }
}
