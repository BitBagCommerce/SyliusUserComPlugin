<?php

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Entity;

trait UserComApiAwareTrait
{
    private string $userComUrl;

    private string $userComApiKey;

    public function getUserComUrl(): string
    {
        return $this->userComUrl;
    }

    public function setUserComUrl(string $userComUrl): void
    {
        $this->userComUrl = $userComUrl;
    }

    public function getUserComApiKey(): string
    {
        return $this->userComApiKey;
    }

    public function setUserComApiKey(string $userComApiKey): void
    {
        $this->userComApiKey = $userComApiKey;
    }
}
