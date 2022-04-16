<?php

declare(strict_types=1);

namespace App\Client\Api;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SmsApiClient extends AbstractApiClient
{
    public function __construct(HttpClientInterface $smsNotificationClient, LoggerInterface $smsNotificationLogger)
    {
        parent::__construct($smsNotificationClient, $smsNotificationLogger);
    }
}
