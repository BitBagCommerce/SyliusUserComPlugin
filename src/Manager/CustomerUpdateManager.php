<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Manager;

use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use BitBag\SyliusUserComPlugin\Updater\CustomerWithKeyUpdaterInterface;
use BitBag\SyliusUserComPlugin\Updater\CustomerWithoutKeyUpdaterInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class CustomerUpdateManager implements CustomerUpdateManagerInterface
{
    public function __construct(
        private readonly CustomerWithKeyUpdaterInterface $customerWithKeyUpdater,
        private readonly CustomerWithoutKeyUpdaterInterface $customerWithoutKeyUpdater,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function manageChange(
        string $eventName,
        UserComApiAwareInterface $apiAwareResource,
        ?CustomerInterface $customer = null,
        ?AddressInterface $address = null,
        ?string $email = null,
        ?string $userKey = null,
    ): array|null {
        try {
            if (null !== $userKey) {
                return $this->customerWithKeyUpdater->updateWithUserKey(
                    $eventName,
                    $userKey,
                    $apiAwareResource,
                    $customer,
                    $address,
                    $email,
                );
            }

            return $this->customerWithoutKeyUpdater->updateWithoutUserKey(
                $eventName,
                $apiAwareResource,
                $customer,
                $address,
                $email,
            );
        } catch (\Throwable $exception) {
            $this->logger->error('User.com - Customer request failed.', [
                'exception' => $exception,
            ]);

            return null;
        }
    }
}
