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

final class UserApi extends AbstractClient implements UserApiInterface
{
    public const PARENT = 'parent';

    public const USERS_LIST = 'users_list';

    public function findUser(
        UserComApiAwareInterface $resource,
        string $value,
        string $field,
    ): ?array {
        $url = $this->getApiEndpointUrl(
            $resource,
            self::FIND_USER_ENDPOINT,
            sprintf('?%s=%s', $field, rawurlencode($value)),
        );

        return $this->request(
            $url,
            Request::METHOD_GET,
            $this->buildOptions($resource),
        );
    }

    public function getUser(UserComApiAwareInterface $resource, int $userId): ?array
    {
        $url = $this->getApiEndpointUrl($resource, sprintf(self::GET_USER_ENDPOINT, $userId));

        return $this->request(
            $url,
            Request::METHOD_GET,
            $this->buildOptions($resource),
        );
    }

    public function getUserByCustomId(UserComApiAwareInterface $resource, string $customId): ?array
    {
        $url = $this->getApiEndpointUrl($resource, sprintf(self::GET_USER_ENDPOINT, $customId));

        return $this->request(
            $url,
            Request::METHOD_GET,
            $this->buildOptions($resource),
        );
    }

    public function updateOrCreateUser(UserComApiAwareInterface $resource, array $data): ?array
    {
        return $this->request(
            $this->getApiEndpointUrl($resource, self::UPDATE_OR_CREATE_USER_ENDPOINT),
            Request::METHOD_POST,
            $this->buildOptions($resource, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]),
        );
    }

    public function updateUser(UserComApiAwareInterface $resource, int $userId, array $data): ?array
    {
        $url = $this->getApiEndpointUrl($resource, sprintf(self::UPDATE_USER_ENDPOINT, $userId));

        return $this->request(
            $url,
            Request::METHOD_PUT,
            $this->buildOptions($resource, ['json' => $data]),
        );
    }

    public function createUser(UserComApiAwareInterface $resource, array $data): ?array
    {
        return $this->request(
            $this->getApiEndpointUrl($resource, self::CREATE_USER_ENDPOINT),
            Request::METHOD_POST,
            $this->buildOptions($resource, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]),
        );
    }

    public function mergeUsers(UserComApiAwareInterface $resource, int $parentId, array $usersList): ?array
    {
        return $this->request(
            $this->getApiEndpointUrl($resource, self::MERGE_USERS_ENDPOINT),
            Request::METHOD_POST,
            $this->buildOptions($resource, [
                'json' => [
                    self::PARENT => $parentId,
                    self::USERS_LIST => $usersList,
                ],
            ]),
        );
    }

    public function updateUserByCustomId(UserComApiAwareInterface $resource, string $customId, array $data): ?array
    {
        $url = $this->getApiEndpointUrl($resource, sprintf(self::UPDATE_USER_BY_CUSTOM_ID_ENDPOINT, $customId));

        return $this->request(
            $url,
            Request::METHOD_PUT,
            $this->buildOptions($resource, ['json' => $data]),
        );
    }

    public function createEventForUser(UserComApiAwareInterface $resource, string $userCustomId, array $data): ?array
    {
        return $this->request(
            $this->getApiEndpointUrl($resource, sprintf(self::CREATE_EVENT_FOR_USER_BY_CUSTOM_ID_ENDPOINT, $userCustomId)),
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
