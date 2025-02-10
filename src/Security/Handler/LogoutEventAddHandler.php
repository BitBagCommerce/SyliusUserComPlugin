<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Security\Handler;

use BitBag\SyliusUserComPlugin\DTO\Event\Logout;
use Spinbits\SyliusGoogleAnalytics4Plugin\Storage\EventsBag;

final class LogoutEventAddHandler
{
    public function __construct(
        private readonly EventsBag $eventsStorage,
    ) {
    }

    public function onLogout(): void
    {
        $this->eventsStorage->setEvent(
            new Logout(),
        );
    }
}
