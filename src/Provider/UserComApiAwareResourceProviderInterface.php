<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Provider;

use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface UserComApiAwareResourceProviderInterface
{
    public function getApiAwareResource(): ?UserComApiAwareInterface;

    public function getApiAwareResourceByOrder(OrderInterface $order): ?UserComApiAwareInterface;

    public function getApiAwareResourceByFormData(string $code): ?UserComApiAwareInterface;

    public function getApiAwareResourceById(mixed $id): ?UserComApiAwareInterface;
}
