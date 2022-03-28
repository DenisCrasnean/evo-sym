<?php

namespace App\Client\Api;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class ApiClient implements ApiClientInterface
{
    private HttpClientInterface $client;

    private LoggerInterface $logger;

    protected string $resourceName;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function fetch(string $method, string $endpoint, array $options = []): object
    {
        $response = null;
        try {
            $response = $this->client->request(
                $method,
                $endpoint,
                $options
            );

            $this->logger->info(
                'Request for '.$this->resourceName.' to '.$endpoint.' endpoint completed successfully!',
                $this->loggerContext($response),
            );
        } catch (BadRequestException $e) {
            $this->logger->error(
                'Request for '.$this->resourceName.' to '.$endpoint.' endpoint failed!',
                [
                    $this->loggerContext($response),
                    'endpoint' => $endpoint,
                    'exception' => $e,
                    'exception_message' => $e->getMessage(),
                ]
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(
                'Request for '.$this->resourceName.' to '.$endpoint.' endpoint failed!',
                [
                    $this->loggerContext($response),
                    'endpoint' => $endpoint,
                    'exception' => $e,
                    'exception_message' => $e->getMessage(),
                ]
            );
            //  TO DO
            //  Implement Custom Exception for missing response
        }

        return $response;
    }

    protected function loggerContext(ResponseInterface $response): array
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
