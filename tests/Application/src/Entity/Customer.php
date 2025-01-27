<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusUserComPlugin\Entity;

use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    private string $userCustomId;

    public function getUserCustomId(): string
    {
        return $this->userCustomId;
    }

    public function setUserCustomId(string $userCustomId): void
    {
        $this->userCustomId = $userCustomId;
    }
}
