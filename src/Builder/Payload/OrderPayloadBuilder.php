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

final class OrderPayloadBuilder implements OrderPayloadBuilderInterface
{
    public function __construct(
        private readonly OrderItemPayloadBuilderInterface $orderItemPayloadBuilder,
    ) {
    }

    public function build(OrderInterface $order): array
    {
        $orderPayload = [
                'custom_id' => $order->getNumber(),
                'name' => sprintf('#%s', $order->getNumber()),
                'user_custom_id' => $order->getCustomer()?->getEmail(),
                'value' => (float) $order->getTotal() / 100,
                'currency' => $order->getCurrencyCode(),
                'stage' => $this->getStage($order),
        ];

        return array_merge(
            $orderPayload,
            $this->orderItemPayloadBuilder->build($order),
        );
    }

    private function getStage(OrderInterface $order): int
    {
        return match ($order->getState()) {
            OrderInterface::STATE_NEW => 1,
            OrderInterface::STATE_FULFILLED => 2,
            OrderInterface::STATE_CANCELLED => 3,
        };
    }
}
