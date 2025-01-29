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

class CustomerWithKeyUpdater extends CustomerWithoutKeyUpdater implements CustomerWithKeyUpdaterInterface
{
    public function __construct(
        CustomerPayloadBuilderInterface $customerPayloadBuilder,
        ChannelContextInterface $channelContext,
        UserApiInterface $userApi,
        CookieManagerInterface $cookieManager,
    ) {
        parent::__construct($customerPayloadBuilder, $channelContext, $userApi, $cookieManager);
    }

    public function updateWithUserKey(
        string $eventName,
        string $userKey,
        ?CustomerInterface $customer = null,
        ?AddressInterface $address = null,
        ?string $email = null,
    ): array|null {
        $apiAwareResource = $this->getApiAwareResource();

        $email = $this->getEmail($customer, $email);
        $payload = $this->buildPayload($email, $customer, $address);

        $userFoundByKey = $this->userApi->findUser(
            $apiAwareResource,
            $userKey,
            UserApiInterface::USER_KEY_PROPERTY,
        );

        if (null === $userFoundByKey) {
            return $this->updateWithoutUserKey(
                $eventName,
                $customer,
                $address,
                $email,
            );
        }

        if (null === $userFoundByKey['email']) {
            return $this->updateForUserWithoutEmail(
                $eventName,
                $apiAwareResource,
                $email,
                $userFoundByKey,
                $customer,
                $address,
            );
        }

        if (null !== $customer && $userFoundByKey['email'] === $customer->getEmail()) {
            return $this->userApi->updateUser(
                $apiAwareResource,
                $userFoundByKey['id'],
                $payload,
            );
        }

        $userByEmailFromForm = $this->userApi->findUser(
            $apiAwareResource,
            $email,
            UserApiInterface::EMAIL_PROPERTY,
        );

        if (null !== $userByEmailFromForm) {
            $user = $this->userApi->updateUser(
                $apiAwareResource,
                $userByEmailFromForm['id'],
                $payload,
            );

            $this->userApi->mergeUsers($apiAwareResource, $userByEmailFromForm['id'], [$userFoundByKey['id']]);
        }

        if (!isset($user)) {
            $user = $this->userApi->createUser($apiAwareResource, $payload);
        }

        if (false === is_array($user) ||
            false === array_key_exists('id', $user) ||
            false === array_key_exists('user_key', $user)
        ) {
            throw new \RuntimeException('User might not be created or updated properly.');
        }

        $this->cookieManager->setUserComCookie($user['user_key']);
        $this->sendEvent($user['email'], $eventName);

        return $user;
    }

    private function updateForUserWithoutEmail(
        string $eventName,
        UserComApiAwareInterface $apiAwareResource,
        string $email,
        array $userFromUserKey,
        ?CustomerInterface $customer = null,
        ?AddressInterface $address = null,
    ): array|null {
        $customerFoundByEmail = $this->userApi->findUser(
            $apiAwareResource,
            $email,
            UserApiInterface::EMAIL_PROPERTY,
        );

        $id = null !== $customerFoundByEmail ?
            $customerFoundByEmail['id'] :
            $userFromUserKey['id'];

        $user = $this->userApi->updateUser(
            $apiAwareResource,
            $id,
            $this->buildPayload($email, $customer, $address),
        );

        if (null !== $customerFoundByEmail) {
            $this->userApi->mergeUsers($apiAwareResource, $id, [$userFromUserKey]);
        }

        $this->sendEvent($email, $eventName);

        return $user;
    }
}
