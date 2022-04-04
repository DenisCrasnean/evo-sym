<?php

namespace App\MessageHandler;

use App\Client\Api\ApiClientInterface;
use App\Client\Api\SmsApiClient;
use App\Message\SmsNotification;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{
    private SmsApiClient $smsNotificationClient;

    private UserRepository $userRepository;

    public function __construct(SmsApiClient $smsNotificationClient, UserRepository $userRepository)
    {
        $this->smsNotificationClient = $smsNotificationClient;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SmsNotification $smsNotification)
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->smsNotificationClient->fetch(
                'POST',
                '/api/messages',
                [
                    'json' => [
                        'receiver' => $user->getPhoneNumber(),
                        'body' => $smsNotification->getContent(),
                    ],
                ]
            );
        }
    }
}
