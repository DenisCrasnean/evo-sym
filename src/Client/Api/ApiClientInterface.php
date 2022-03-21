<?php

namespace App\Client\Api;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface ApiClientInterface
{
    public function __construct(HttpClientInterface $client, LoggerInterface $logger);

    public function fetch(string $method, string $endpoint, array $options = []);
}
