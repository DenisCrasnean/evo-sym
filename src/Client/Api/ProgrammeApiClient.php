<?php

declare(strict_types=1);

namespace App\Client\Api;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProgrammeApiClient extends ApiClient
{
    protected string $resourceName = 'programmes';

    public function __construct(HttpClientInterface $programmeClient, LoggerInterface $programmeLogger)
    {
        $test = "Just a test becaue I cleaned up dome branches";
        parent::__construct($programmeClient, $programmeLogger);
    }
}
