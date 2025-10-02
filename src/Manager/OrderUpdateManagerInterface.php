<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Manager;

use Sylius\Component\Core\Model\OrderInterface;

interface OrderUpdateManagerInterface
{
    public const PRODUCT_EVENT_MAP = [
        OrderInterface::STATE_NEW => 'order',
        OrderInterface::STATE_FULFILLED => 'purchase',
        OrderInterface::STATE_CANCELLED => 'remove',
    ];

    public function handle(OrderInterface $order): void;
}
