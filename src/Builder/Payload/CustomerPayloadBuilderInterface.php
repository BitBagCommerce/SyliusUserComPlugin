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

interface CustomerPayloadBuilderInterface
{
    public const GENDER_MAP = [
        CustomerInterface::FEMALE_GENDER => 3,
        CustomerInterface::MALE_GENDER => 2,
        CustomerInterface::UNKNOWN_GENDER => 1,
    ];

    public const STATUS_USER = 2;

    public const STATUS_VISITOR = 1;

    public function build(string $email, ?CustomerInterface $customer = null, ?AddressInterface $address = null): array;
}
