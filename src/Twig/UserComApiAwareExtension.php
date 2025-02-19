<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Twig;

use BitBag\SyliusUserComPlugin\Provider\UserComApiAwareResourceProviderInterface;
use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class UserComApiAwareExtension extends AbstractExtension
{
    public function __construct(
        private readonly UserComApiAwareResourceProviderInterface $userComApiAwareResourceProvider,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getUserComApiAwareResource', [$this, 'getUserComApiAware']),
        ];
    }

    public function getUserComApiAware(): ?UserComApiAwareInterface
    {
        return $this->userComApiAwareResourceProvider->getApiAwareResource();
    }
}
