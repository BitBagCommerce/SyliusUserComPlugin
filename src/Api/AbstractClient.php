<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Api;

use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractClient
{
    private const API_ENDPOINT_PREFIX = '/api/public/';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface $logger,
    ) {
    }

    protected function request(
        string $path,
        string $method,
        array $options,
    ): ?array {
        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request(
                $method,
                $path,
                $options,
            );

            $status = $response->getStatusCode();
            if ($status >= Response::HTTP_OK && $status < Response::HTTP_MULTIPLE_CHOICES) {
                return $response->toArray();
            }

            throw new \Exception(
                sprintf(
                    'Response status code : %s, response : %s',
                    $status,
                    $response->getContent(false),
                ),
            );
        } catch (\Exception $e) {
            $this->logger->critical(sprintf(
                'User.com API request failed: %s',
                $e->getMessage(),
            ), [
                'path' => $path,
                'method' => $method,
                'options' => $options,
            ]);

            return null;
        }
    }

    protected function getApiEndpointUrl(
        UserComApiAwareInterface $resource,
        string $endpoint,
        string $query = null,
    ): string {
        $url = sprintf(
            '%s/%s/%s/',
            trim($resource->getUserComUrl(), '/'),
            trim(self::API_ENDPOINT_PREFIX, '/'),
            trim($endpoint, '/'),
        );

        if (null === $query) {
            return $url;
        }

        return sprintf('%s%s/', $url, $query);
    }

    protected function buildOptions(
        UserComApiAwareInterface $resource,
        array $options = [],
    ): array {
        $options['headers']['Accept'] = ' */*; version=2';
        $options['headers']['Authorization'] = $this->authorizeRequest($resource);

        return $options;
    }

    protected function authorizeRequest(
        UserComApiAwareInterface $resource,
    ): string {
        return sprintf(
            'Token %s',
            $resource->getUserComApiKey(),
        );
    }
}
