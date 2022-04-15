<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountCreaatedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [DoctrineEve::class => 'onPostSubmit'];
    }

    public function onPostSubmit( $event): void
    {

    }
}
