<?php

namespace App\Controller;

use App\Message\EmailNotification;
use App\Message\SmsNotification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("api/messages", methods={"POST"}, name="app_messages_sms")
     */
    public function sendSmsNotificationAction(): Response
    {
        $this->bus
            ->dispatch(new SmsNotification('Look! I created a message!'));

        $this->bus
            ->dispatch(new EmailNotification('Am trimis un mail cu symfony messenger, ce ma bucur, yeeey!'));

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
