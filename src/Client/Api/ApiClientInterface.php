<?php

namespace App\Client\Api;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiClientInterface
{
    public function __construct(HttpClientInterface $client, LoggerInterface $logger);

    public function fetch(string $method, string $url, array $options = []): ResponseInterface;
}
