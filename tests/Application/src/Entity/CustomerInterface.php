<?php


declare(strict_types=1);

namespace Tests\BitBag\SyliusUserComPlugin\Entity;

use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface CustomerInterface extends BaseCustomerInterface
{
    public function getUserCustomId(): string;

    public function setUserCustomId(string $userCustomId): void;
}
