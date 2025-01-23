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
use Symfony\Component\HttpFoundation\Request;

final class DealApi extends AbstractClient implements DealApiInterface
{
    public function updateDealByCustomId(UserComApiAwareInterface $resource, string $customId, array $data): ?array
    {
        $url = $this->getApiEndpointUrl($resource, sprintf(self::UPDATE_DEAL_ENDPOINT, $customId));

        return $this->request(
            $url,
            Request::METHOD_PUT,
            $this->buildOptions($resource, ['json' => $data]),
        );
    }

    public function createDeal(UserComApiAwareInterface $resource, array $data): ?array
    {
        return $this->request(
            $this->getApiEndpointUrl($resource, self::CREATE_DEAL_ENDPOINT),
            Request::METHOD_POST,
            $this->buildOptions($resource, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]),
        );
    }

    public function updateOrCreateDeal(UserComApiAwareInterface $resource, array $data): ?array
    {
        return $this->request(
            $this->getApiEndpointUrl($resource, self::CREATE_OR_UPDATE_DEAL_ENDPOINT),
            Request::METHOD_POST,
            $this->buildOptions($resource, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]),
        );
    }
}
