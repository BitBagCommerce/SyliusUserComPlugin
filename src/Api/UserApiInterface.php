<?php

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Api;

use BitBag\SyliusUserComPlugin\Entity\UserComApiAwareInterface;

interface UserApiInterface
{
    public const EMAIL_PROPERTY = 'email';

    public const USER_KEY_PROPERTY = 'key';

    public const FIND_USER_ENDPOINT = 'users/search';

    public const UPDATE_OR_CREATE_USER_ENDPOINT = '/users/update_or_create/';

    public const UPDATE_USER_ENDPOINT = '/users/%s';

    public const UPDATE_USER_BY_CUSTOM_ID_ENDPOINT = '/users-by-id/%s';

    public const GET_USER_ENDPOINT = '/users/%s';

    public const CREATE_USER_ENDPOINT = '/users/';

    public const MERGE_USERS_ENDPOINT = '/users/merge';

    public function findUser(
        UserComApiAwareInterface $resource,
        string $value,
        string $field,
    ): ?array;

    public function updateOrCreateUser(UserComApiAwareInterface $resource, array $data): ?array;

    public function updateUser(UserComApiAwareInterface $resource, int $userId, array $data): ?array;

    public function createUser(UserComApiAwareInterface $resource, array $data): ?array;

    public function mergeUsers(UserComApiAwareInterface $resource, int $parentId, array $usersList): ?array;
}
