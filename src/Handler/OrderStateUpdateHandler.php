<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Handler;

use BitBag\SyliusUserComPlugin\Api\DealApiInterface;
use BitBag\SyliusUserComPlugin\Api\ProductApiInterface;
use BitBag\SyliusUserComPlugin\Builder\Payload\OrderPayloadBuilderInterface;
use BitBag\SyliusUserComPlugin\Builder\Payload\ProductEventPayloadBuilderInterface;
use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class OrderStateUpdateHandler implements OrderStateUpdateHandlerInterface
{
    public function __construct(
        private readonly OrderPayloadBuilderInterface $orderPayloadBuilder,
        private readonly ProductEventPayloadBuilderInterface $productEventPayloadBuilder,
        private readonly DealApiInterface $dealApi,
        private readonly ProductApiInterface $productApi,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function handle(OrderInterface $order): void
    {
        $channel = $order->getChannel();
        if (false === $channel instanceof UserComApiAwareInterface) {
            throw new \InvalidArgumentException('Channel must implement UserComApiAwareInterface');
        }

        if (null === $channel->getUserComUrl() || null === $channel->getUserComApiKey()) {
            return;
        }

        if (null === $order->getNumber()) {
            throw new \InvalidArgumentException('Order number cannot be null while sending order data to User.com');
        }

        $customer = $order->getCustomer();
        Assert::notNull($customer, 'Order customer cannot be null while sending order data to User.com');

        $email = $customer->getEmail();
        Assert::notNull($email, 'Order customer email cannot be null while sending order data to User.com');
        $this->createProductEvents($order, $channel, $email);

        if (OrderInterface::STATE_NEW === $order->getState()) {
            $this->dealApi->createDeal($channel, $this->orderPayloadBuilder->build($order), $email);

            return;
        }

        if (OrderInterface::STATE_FULFILLED === $order->getState() || OrderInterface::STATE_CANCELLED === $order->getState()) {
            $orderNumber = $order->getNumber();
            Assert::notNull($orderNumber, 'Order number cannot be null while sending order data to User.com');

            $this->dealApi->updateDealByCustomId(
                $channel,
                $orderNumber,
                $this->orderPayloadBuilder->build($order),
            );

            return;
        }

        throw new \RuntimeException(
            sprintf(
                'Order #%s state "%s" is not supported.',
                $order->getNumber(),
                $order->getState(),
            ),
        );
    }

    private function createProductEvents(
        OrderInterface $order,
        UserComApiAwareInterface $resource,
        string $email,
    ): void {
        $eventType = self::PRODUCT_EVENT_MAP[$order->getState()] ?? null;
        if (null === $eventType) {
            $this->logger->warning(sprintf('Order #%s state "%s" is not supported.', $order->getNumber(), $order->getState()));

            return;
        }

        foreach ($order->getItems() as $orderItem) {
            $variant = $orderItem->getVariant();
            if (null === $variant) {
                $this->logger->warning(sprintf('Order item #%s does not have a variant.', $orderItem->getId()));

                continue;
            }
            $product = $variant->getProduct();

            $this->productApi->createProductEventByCustomId(
                $resource,
                $variant->getId(),
                $this->productEventPayloadBuilder->build($eventType, $variant, $email),
                sprintf('%s - %s', $product?->getName(), $variant->getName()),
            );
        }
    }
}
