<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Authenticator;

use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Component\HttpFoundation\Request;

final class RequestAuthenticator implements RequestAuthenticatorInterface
{
    public function __construct(
        private readonly ChannelContextInterface $channelContext,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function authenticate(Request $request): bool
    {
        $channel = $this->channelContext->getChannel();
        if (!$channel instanceof UserComApiAwareInterface) {
            return false;
        }

        $apiKey = $channel->getUserComApiKey();
        $requestApiKey = $request->headers->get('X-User-Com-Signature');
        if (null === $requestApiKey) {
            return false;
        }

        $content = $request->getContent(false);

        $content = json_encode(
            json_decode($content, true),
            \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
        );

        if (false === is_string($content)) {
            $this->logger->warning('User.com - Invalid JSON content.');

            throw new \InvalidArgumentException('Invalid JSON content.');
        }

        if (null === $apiKey) {
            $this->logger->warning('User.com - Missing API key.');

            return false;
        }

        $signature = hash_hmac('sha256', $content, $apiKey);

        return hash_equals($signature, $requestApiKey);
    }
}
