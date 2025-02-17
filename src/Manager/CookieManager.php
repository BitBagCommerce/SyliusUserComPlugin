<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Manager;

use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

final class CookieManager implements CookieManagerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly Security $security,
    ) {
    }

    public function getUserComCookie(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return null;
        }

        if (!$this->isShopUser()) {
            return null;
        }

        $value = $request->cookies->get(self::CHAT_COOKIE_NAME);

        return null === $value ? null : (string) $value;
    }

    public function setUserComCookie(string $value): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return;
        }
        $request->cookies->set(self::CHAT_COOKIE_NAME, $value);
    }

    private function isShopUser(): bool
    {
        $user = $this->security->getUser();

        if ($user instanceof ShopUserInterface) {
            return true;
        }

        if ($user instanceof AdminUserInterface) {
            return false;
        }

        return true;
    }
}
