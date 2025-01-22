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

interface DealApiInterface
{
    public const CREATE_DEAL_ENDPOINT = '/deals/';

    public const UPDATE_DEAL_ENDPOINT = '/deals-by-id/%s/';

    public const CREATE_OR_UPDATE_DEAL_ENDPOINT = '/deals/update_or_create/';

    public function updateDealByCustomId(UserComApiAwareInterface $resource, string $customId, array $data): ?array;

    public function createDeal(UserComApiAwareInterface $resource, array $data): ?array;

    public function updateOrCreateDeal(UserComApiAwareInterface $resource, array $data): ?array;
}
