<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Dispatcher;

use BitBag\SyliusUserComPlugin\Message\OrderSynchronization;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderMessageDispatcher implements OrderMessageDispatcherInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    ) {
    }

    public function dispatch(OrderInterface $order): void
    {
        if (null === $order->getId()) {
            $this->logger->error('Order ID cannot be null');

            return;
        }

        $this->messageBus->dispatch(new OrderSynchronization($order->getId()));
    }
}
