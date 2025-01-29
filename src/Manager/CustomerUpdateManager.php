<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Manager;

use BitBag\SyliusUserComPlugin\Updater\CustomerWithKeyUpdaterInterface;
use BitBag\SyliusUserComPlugin\Updater\CustomerWithoutKeyUpdaterInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class CustomerUpdateManager implements CustomerUpdateManagerInterface
{
    public function __construct(
        private readonly CookieManagerInterface $cookieManager,
        private readonly CustomerWithKeyUpdaterInterface $customerWithKeyUpdater,
        private readonly CustomerWithoutKeyUpdaterInterface $customerWithoutKeyUpdater,
    ) {
    }

    public function manageChange(
        string $eventName,
        ?CustomerInterface $customer = null,
        ?AddressInterface $address = null,
        ?string $email = null,
    ): array|null {
        $userKey = $this->cookieManager->getUserComCookie();

        if (null !== $userKey) {
            return $this->customerWithKeyUpdater->updateWithUserKey(
                $eventName,
                $userKey,
                $customer,
                $address,
                $email,
            );
        }

        return $this->customerWithoutKeyUpdater->updateWithoutUserKey(
            $eventName,
            $customer,
            $address,
            $email,
        );
    }
}
