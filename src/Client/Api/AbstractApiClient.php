<?php

namespace App\Client\Api;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractApiClient implements ApiClientInterface
{
    private HttpClientInterface $client;

    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function fetch(string $method, string $url, array $options = []): ResponseInterface
    {
        $response = null;
        try {
            $response = $this->client->request(
                $method,
                $url,
                $options
            );

            $this->logger->info(
                'Request for '.$url.' endpoint completed successfully!',
                $this->defaultLoggerContext($response),
            );
        } catch (RequestExceptionInterface $e) {
            $this->logger->error(
                'Request for '.$url.' endpoint failed!',
                [
                    $this->defaultLoggerContext($response),
                    'endpoint' => $url,
                    'exception' => $e,
                    'exception_message' => $e->getMessage(),
                ]
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(
                'Request for '.$url.' endpoint failed!',
                [
                    $this->defaultLoggerContext($response),
                    'endpoint' => $url,
                    'exception' => $e,
                    'exception_message' => $e->getMessage(),
                ]
            );
        }

        return $response;
    }

    public function defaultLoggerContext(ResponseInterface $response): array
    {
        return [
            'status_code' => $response->getStatusCode(),
            'date' => $response->getHeaders()['date'][0],
            'start_time' => $response->getInfo('start_time'),
            'total_time' => $response->getInfo('total_time'),
            'redirect_count' => $response->getInfo('redirect_count'),
            'redirect_url' => $response->getInfo('redirect_url'),
        ];
    }
}
