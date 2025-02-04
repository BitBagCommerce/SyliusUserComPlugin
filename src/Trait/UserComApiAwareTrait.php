<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Trait;

trait UserComApiAwareTrait
{
    private ?string $userComUrl;

    private ?string $userComApiKey;

    public function getUserComUrl(): ?string
    {
        return $this->userComUrl;
    }

    public function setUserComUrl(string $userComUrl): void
    {
        $this->userComUrl = $userComUrl;
    }

    public function getUserComApiKey(): ?string
    {
        return $this->userComApiKey;
    }

    public function setUserComApiKey(?string $userComApiKey): void
    {
        $this->userComApiKey = $userComApiKey;
    }
}
