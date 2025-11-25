<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Cookie;

use Symfony\Component\HttpFoundation\Cookie;

final class CookieQueue implements CookieQueueInterface
{
    private array $queued = [];

    public function queue(Cookie $cookie): void
    {
        $this->queued[] = $cookie;
    }

    /** @return Cookie[] */
    public function pullAll(): array
    {
        $all = $this->queued;
        $this->queued = [];

        return $all;
    }
}
