<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Builder\Payload;

use Sylius\Component\Core\Model\OrderInterface;

final class OrderItemPayloadBuilder implements OrderItemPayloadBuilderInterface
{
    public function build(OrderInterface $order): array
    {
        $currency = $order->getCurrencyCode();
        $orderItemCodes = [];
        foreach ($order->getItems() as $orderItem) {
            $variant = $orderItem->getVariant();
            $orderItemCodes[] = $variant?->getId();

            $orderProducts[] = [
                'product_custom_id' => $variant?->getId(),
                'quantity' => $orderItem->getQuantity(),
                'value' => (float) $orderItem->getUnitPrice() / 100,
                'currency' => $currency,
            ];
        }

        return[
            'order_products' => $orderProducts,
            'products_custom_id' => $orderItemCodes,
        ];
    }
}
