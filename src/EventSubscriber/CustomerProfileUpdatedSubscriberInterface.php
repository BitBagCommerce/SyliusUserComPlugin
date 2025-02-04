<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface CustomerProfileUpdatedSubscriberInterface extends EventSubscriberInterface
{
    public const DEFAULT_EVENT = 'undefined_event_name';

    public const API_INTEGRATION_ROUTES = [
        'sylius_shop_checkout_address',
        'sylius_shop_register',
    ];

    public const FORM_NAME_TO_EVENT_MAP = [
        'sylius_customer_profile' => 'customer_profile_update',
        'sylius_admin_customer_update' => 'admin_customer_update',
        'sylius_customer_registration' => 'customer_registration',
        'sylius_checkout_address' => 'customer_order_address_provided',
    ];
}
