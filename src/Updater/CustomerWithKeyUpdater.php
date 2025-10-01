<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Updater;

use BitBag\SyliusUserComPlugin\Api\AbstractClient;
use BitBag\SyliusUserComPlugin\Api\UserApiInterface;
use BitBag\SyliusUserComPlugin\Builder\Payload\CustomerPayloadBuilderInterface;
use BitBag\SyliusUserComPlugin\Manager\CookieManagerInterface;
use BitBag\SyliusUserComPlugin\Provider\UserComApiAwareResourceProviderInterface;
use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;

class CustomerWithKeyUpdater extends CustomerWithoutKeyUpdater implements CustomerWithKeyUpdaterInterface
{
    public function __construct(
        CustomerPayloadBuilderInterface $customerPayloadBuilder,
        UserApiInterface $userApi,
        CookieManagerInterface $cookieManager,
        UserComApiAwareResourceProviderInterface $provider,
    ) {
        parent::__construct(
            $customerPayloadBuilder,
            $userApi,
            $cookieManager,
            $provider,
        );
    }

    public function updateWithUserKey(
        string $eventName,
        string $userKey,
        UserComApiAwareInterface $apiAwareResource,
        ?CustomerInterface $customer = null,
        ?AddressInterface $address = null,
        ?string $email = null,
    ): array|null {
        $email = $this->getEmail($customer, $email);
        $payload = $this->buildPayload($email, $customer, $address);

        $userFoundByKey = $this->userApi->findUser(
            $apiAwareResource,
            $userKey,
            UserApiInterface::USER_KEY_PROPERTY,
        );

        if (null === $userFoundByKey || array_key_exists(AbstractClient::ERROR, $userFoundByKey)) {
            return $this->updateWithoutUserKey(
                $eventName,
                $apiAwareResource,
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

        if (null !== $customer &&
            null !== $customer->getEmail() &&
            $userFoundByKey['email'] === strtolower($customer->getEmail())
        ) {
            $user = $this->userApi->updateUser(
                $apiAwareResource,
                $userFoundByKey['id'],
                $payload,
            );

            if (false === is_array($user) || false === array_key_exists('email', $user)) {
                throw new \RuntimeException('User was not created or updated.');
            }

            $this->sendEvent($apiAwareResource, $user['email'], $eventName, $payload);

            return $user;
        }

        $userByEmailFromForm = $this->userApi->findUser(
            $apiAwareResource,
            $email,
            UserApiInterface::EMAIL_PROPERTY,
        );

        if (null !== $userByEmailFromForm &&
            false === array_key_exists(AbstractClient::ERROR, $userByEmailFromForm)
        ) {
            $user = $this->userApi->updateUser(
                $apiAwareResource,
                $userByEmailFromForm['id'],
                $payload,
            );

            $this->userApi->mergeUsers($apiAwareResource, $userByEmailFromForm['id'], [$userFoundByKey['id']]);
            $this->changeCookieWithEvent($user, $apiAwareResource, $eventName, $payload);

            return $user;
        }

        $user = $this->userApi->createUser($apiAwareResource, $payload);
        $this->changeCookieWithEvent($user, $apiAwareResource, $eventName, $payload);

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

        $customerFoundByEmailId = null !== $customerFoundByEmail && isset($customerFoundByEmail['id'])
            ? $customerFoundByEmail['id']
            : null
        ;

        $id = $customerFoundByEmailId ?? $userFromUserKey['id'];
        $payload = $this->buildPayload($email, $customer, $address);

        $user = $this->userApi->updateUser(
            $apiAwareResource,
            $id,
            $payload,
        );

        if (is_array($customerFoundByEmail) &&
            array_key_exists('id', $customerFoundByEmail) &&
            array_key_exists('id', $userFromUserKey)
        ) {
            $this->userApi->mergeUsers($apiAwareResource, $customerFoundByEmail['id'], [$userFromUserKey['id']]);
        }

        $this->sendEvent($apiAwareResource, $email, $eventName, $payload);

        return $user;
    }

    public function changeCookieWithEvent(
        ?array $user,
        UserComApiAwareInterface $apiAwareResource,
        string $eventName,
        ?array $payload = null,
    ): void {
        if (false === is_array($user) ||
            false === array_key_exists('id', $user) ||
            false === array_key_exists('user_key', $user)
        ) {
            throw new \RuntimeException('User might not be created or updated properly.');
        }

        $this->cookieManager->setUserComCookie($user['user_key']);
        $this->sendEvent($apiAwareResource, $user['email'], $eventName, $payload);
    }
}
