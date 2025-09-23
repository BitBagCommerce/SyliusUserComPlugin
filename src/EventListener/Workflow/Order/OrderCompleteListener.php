<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\EventListener\Workflow\Order;

use BitBag\SyliusUserComPlugin\Message\OrderSynchronization;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Event\Event;

final readonly class OrderCompleteListener
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(Event $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        if (null === $order->getId()) {
            $this->logger->error('User.com - Order ID cannot be null');

            return;
        }

        $this->messageBus->dispatch(new OrderSynchronization($order->getId()));
    }
}
