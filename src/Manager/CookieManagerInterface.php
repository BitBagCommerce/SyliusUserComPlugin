<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Manager;

interface CookieManagerInterface
{
    public const CHAT_COOKIE_NAME = '__ca__chat';

    public function getUserComCookie(): ?string;

    public function setUserComCookie(string $value): void;
}
