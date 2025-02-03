<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Builder\Payload;

use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class CustomerPayloadBuilder implements CustomerPayloadBuilderInterface
{
    public function build(string $email, ?CustomerInterface $customer = null, ?AddressInterface $address = null): array
    {
        if (null !== $customer && null === $address) {
            $address = $customer->getDefaultAddress();
        }

        $payload = [
            'custom_id' => strtolower($customer?->getEmail() ?? $email),
            'email' => strtolower($customer?->getEmail() ?? $email),
            'firstName' => $customer?->getFirstName(),
            'lastName' => $customer?->getLastName(),
            'phone_number' => $customer?->getPhoneNumber(),
            'country' => $address?->getCountryCode(),
            'region' => $address?->getProvinceCode(),
            'city' => $address?->getCity(),
            'gender' => null !== $customer ? self::GENDER_MAP[$customer->getGender()] : null,
            'status' => null !== $customer ? self::STATUS_USER : self::STATUS_VISITOR,
            'unsubscribed' => null !== $customer ? !$customer->isSubscribedToNewsletter() : null,
        ];

        return array_filter($payload, fn ($value) => null !== $value);
    }
}
