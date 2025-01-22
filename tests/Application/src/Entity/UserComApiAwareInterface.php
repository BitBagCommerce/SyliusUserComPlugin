<?php

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Entity;

interface UserComApiAwareInterface
{
    public function getUserComUrl(): string;

    public function setUserComUrl(string $userComUrl): void;

    public function getUserComApiKey(): string;

    public function setUserComApiKey(string $userComApiKey): void;
}
