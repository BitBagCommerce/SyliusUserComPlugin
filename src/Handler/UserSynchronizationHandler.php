<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Handler;

use BitBag\SyliusUserComPlugin\Manager\CustomerUpdateManagerInterface;
use BitBag\SyliusUserComPlugin\Message\UserSynchronization;
use BitBag\SyliusUserComPlugin\Provider\UserComApiAwareResourceProviderInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\AddressRepositoryInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Webmozart\Assert\Assert;

final class UserSynchronizationHandler
{
    public function __construct(
        private readonly RepositoryInterface $addressRepository,
        private readonly RepositoryInterface $customerRepository,
        private readonly UserComApiAwareResourceProviderInterface $userComApiAwareResourceProvider,
        private readonly CustomerUpdateManagerInterface $updateManager,
    ) {
    }

    public function __invoke(UserSynchronization $userSynchronization): void
    {
        Assert::isInstanceOf($this->customerRepository, CustomerRepositoryInterface::class);
        Assert::isInstanceOf($this->addressRepository, AddressRepositoryInterface::class);

        $customer = $this->customerRepository->find($userSynchronization->getCustomerId());
        Assert::isInstanceOf($customer, CustomerInterface::class);

        $email = $userSynchronization->getEmail();

        $addressId = $userSynchronization->getAddressId();
        $address = $this->getAddress($addressId);

        $resourceId = $userSynchronization->getUserComApiAwareResourceId();

        $userComApiAwareResource = $this->userComApiAwareResourceProvider->getApiAwareResourceById($resourceId);
        if (null === $userComApiAwareResource) {
            throw new UnrecoverableMessageHandlingException('User.com API-aware resource not found');
        }

        $eventName = $userSynchronization->getEventName();

        $this->updateManager->manageChange(
            $eventName,
            $userComApiAwareResource,
            $customer,
            $address,
            $email,
            $userSynchronization->getUserComCookie(),
        );
    }

    private function getAddress(?int $addressId): ?AddressInterface
    {
        $address = null !== $addressId ? $this->addressRepository->find($addressId) : null;
        if (null === $address) {
            return null;
        }

        if (!$address instanceof AddressInterface) {
            throw new UnrecoverableMessageHandlingException('Address not found');
        }

        return $address;
    }
}
