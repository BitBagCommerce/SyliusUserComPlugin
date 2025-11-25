<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\EventSubscriber;

use BitBag\SyliusUserComPlugin\Cookie\CookieQueueInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class CookieFlusherSubscriber
{
    public function __construct(private readonly CookieQueueInterface $queue)
    {
    }

    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        foreach ($this->queue->pullAll() as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }
}
