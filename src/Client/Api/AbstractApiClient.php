<?php

namespace App\Client\Api;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractApiClient implements ApiClientInterface
{
    private HttpClientInterface $client;

    private LoggerInterface $logger;

    private ResponseInterface $response;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function fetch(string $method, string $url, array $options = []): ResponseInterface
    {
        try {
            $this->response = $this->client->request(
                $method,
                $url,
                $options
            );

            $this->logger->info(
                'Request for '.$url.' endpoint completed successfully!',
                $this->defaultLoggerContext($this->response),
            );

            return $this->response;
        } catch (ServerExceptionInterface|TooManyRequestsHttpException|UnauthorizedHttpException|TimeoutException|
            RequestExceptionInterface|TransportExceptionInterface $e) {
            $this->logger->error(
                'Request for '.$url.' endpoint failed!',
                [
                    $this->defaultLoggerContext($this->response),
                    'endpoint' => $url,
                    'exception' => $e,
                    'exception_message' => $e->getMessage(),
                ]
            );
        }

        return $this->response;
    }

    public function defaultLoggerContext(ResponseInterface $response): array
    {
        try {
            return [
                'status_code' => $response->getStatusCode(),
                'date' => $response->getHeaders()['date'][0],
                'start_time' => $response->getInfo('start_time'),
                'total_time' => $response->getInfo('total_time'),
                'redirect_count' => $response->getInfo('redirect_count'),
                'redirect_url' => $response->getInfo('redirect_url'),
            ];
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            return [
                'exception' => $e,
                'message' => $e->getMessage(),
            ];
        }
    }
}
