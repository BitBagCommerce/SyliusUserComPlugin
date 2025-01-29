<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Updater;

use BitBag\SyliusUserComPlugin\Api\UserApiInterface;
use BitBag\SyliusUserComPlugin\Builder\Payload\CustomerPayloadBuilderInterface;
use BitBag\SyliusUserComPlugin\Manager\CookieManagerInterface;
use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Webmozart\Assert\Assert;

class CustomerWithoutKeyUpdater implements CustomerWithoutKeyUpdaterInterface
{
    public function __construct(
        protected CustomerPayloadBuilderInterface $customerPayloadBuilder,
        protected ChannelContextInterface $channelContext,
        protected UserApiInterface $userApi,
        protected CookieManagerInterface $cookieManager,
    ) {
    }

    public function updateWithoutUserKey(
        string $eventName,
        ?CustomerInterface $customer = null,
        ?AddressInterface $address = null,
        ?string $email = null,
    ): array|null {
        $userApiAwareResource = $this->getApiAwareResource();

        $email = $this->getEmail($customer, $email);

        $customerFoundByEmail = $this->userApi->findUser(
            $userApiAwareResource,
            $email,
            UserApiInterface::EMAIL_PROPERTY,
        );

        $payload = $this->buildPayload($email, $customer, $address);

        if (null !== $customerFoundByEmail) {
            $user = $this->userApi->updateUser(
                $userApiAwareResource,
                $customerFoundByEmail['id'],
                $payload,
            );
        } else {
            $user = $this->userApi->createUser($userApiAwareResource, $payload);
        }

        if (false === is_array($user) || false === array_key_exists('id', $user)) {
            throw new \RuntimeException('User was not created or updated.');
        }
        $this->sendEvent($user['id'], $eventName);

        return $user;
    }

    protected function buildPayload(
        string $email,
        ?CustomerInterface $customer = null,
        ?AddressInterface $address = null,
    ): array {
        return $this->customerPayloadBuilder->build($email, $customer, $address);
    }

    protected function getApiAwareResource(): UserComApiAwareInterface
    {
        $apiAwareResource = $this->channelContext->getChannel();
        Assert::isInstanceOf($apiAwareResource, UserComApiAwareInterface::class);

        return $apiAwareResource;
    }

    protected function sendEvent(string $userCustomId, string $event = null): void
    {
        $this->userApi->createEventForUser(
            $this->getApiAwareResource(),
            $userCustomId,
            [
                'data' => [
                    'event' => $event,
                ],
            ],
        );
    }

    protected function getEmail(?CustomerInterface $customer, ?string $email): string
    {
        if (null === $customer && null === $email) {
            throw new \RuntimeException('You need to provide either a customer or an email.');
        }

        $email = $email ?? $customer->getEmail();
        Assert::notNull($email, 'Could not find email in the customer or in the request.');

        return $email;
    }
}
