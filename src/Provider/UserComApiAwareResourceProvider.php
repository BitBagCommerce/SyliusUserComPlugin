<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Provider;

use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class UserComApiAwareResourceProvider implements UserComApiAwareResourceProviderInterface
{
    public function __construct(
        private readonly RepositoryInterface $channelRepository,
        private readonly ChannelContextInterface $channelContext,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getApiAwareResource(): ?UserComApiAwareInterface
    {
        $resource = $this->channelContext->getChannel();
        if (!$resource instanceof UserComApiAwareInterface) {
            $this->addLogMessage('getApiAwareResource');

            return null;
        }

        return $resource;
    }

    public function getApiAwareResourceByOrder(OrderInterface $order): ?UserComApiAwareInterface
    {
        $resource = $order->getChannel();
        if (!$resource instanceof UserComApiAwareInterface) {
            $this->addLogMessage('getApiAwareResourceByOrder');

            return null;
        }

        return $resource;
    }

    public function getApiAwareResourceByFormData(string $code): ?UserComApiAwareInterface
    {
        Assert::isInstanceOf($this->channelRepository, ChannelRepositoryInterface::class);
        $resource = $this->channelRepository->findOneByCode($code);

        if (!$resource instanceof UserComApiAwareInterface) {
            $this->addLogMessage('getApiAwareResourceByFormData');

            return null;
        }

        return $resource;
    }

    public function getApiAwareResourceById(mixed $id): ?UserComApiAwareInterface
    {
        Assert::isInstanceOf($this->channelRepository, ChannelRepositoryInterface::class);
        $resource = $this->channelRepository->find($id);

        if (!$resource instanceof UserComApiAwareInterface) {
            $this->addLogMessage('getApiAwareResourceById');

            return null;
        }

        return $resource;
    }

    private function addLogMessage(string $method): void
    {
        $this->logger->warning('User.com API aware resource not found using ' . $method);
    }
}
