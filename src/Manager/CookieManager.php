<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Manager;

use BitBag\SyliusUserComPlugin\Cookie\CookieQueueInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CookieManager implements CookieManagerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly CookieQueueInterface $queue,
        private readonly ?string $cookieDomain = null,
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
        $cookie = Cookie::create(self::CHAT_COOKIE_NAME)
            ->withValue($value)
            ->withPath('/')
            ->withSecure(true)
            ->withExpires(new \DateTimeImmutable('+1 year'))
            ->withHttpOnly(false)
            ->withSameSite('lax');

        if (null !== $this->cookieDomain && '' !== $this->cookieDomain) {
            $cookie = $cookie->withDomain($this->cookieDomain);
        } else {
            $request = $this->requestStack->getCurrentRequest();
            if (null === $request) {
                return;
            }

            $domain = $this->getBaseDomain($request->getHost());
            if (null !== $domain) {
                $cookie = $cookie->withDomain($domain);
            }
        }

        $this->queue->queue($cookie);
    }

    private function isShopUser(): bool
    {
        $token = $this->tokenStorage->getToken();
        $user = $token?->getUser();

        if ($user instanceof AdminUserInterface) {
            return false;
        }

        return true;
    }

    private function getBaseDomain(string $host): ?string
    {
        $host = (string) preg_replace('/:\d+$/', '', $host);

        if ($host === 'localhost' || filter_var($host, \FILTER_VALIDATE_IP) !== false) {
            return null;
        }

        $parts = explode('.', $host);
        $count = count($parts);

        if ($count >= 2) {
            return '.' . $parts[$count - 2] . '.' . $parts[$count - 1];
        }

        return null;
    }
}
