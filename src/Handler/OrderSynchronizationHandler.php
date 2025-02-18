<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Handler;

use BitBag\SyliusUserComPlugin\Manager\OrderUpdateManagerInterface;
use BitBag\SyliusUserComPlugin\Message\OrderSynchronization;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

#[AsMessageHandler]
final class OrderSynchronizationHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly RepositoryInterface $orderRepository,
        private readonly OrderUpdateManagerInterface $orderUpdateManager,
    ) {
    }

    public function __invoke(OrderSynchronization $orderSynchronization): void
    {
        Assert::isInstanceOf($this->orderRepository, OrderRepositoryInterface::class);
        $order = $this->orderRepository->find($orderSynchronization->getOrderId());
        if (!$order instanceof OrderInterface) {
            throw new UnrecoverableMessageHandlingException(
                sprintf(
                    'Order with id %s has not been found.',
                    $orderSynchronization->getOrderId(),
                ),
            );
        }

        $this->orderUpdateManager->handle($order);
    }
}
