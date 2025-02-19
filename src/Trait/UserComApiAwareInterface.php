<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Trait;

interface UserComApiAwareInterface
{
    /**
     * To ensure compatibility with ResourceInterface, return type must be missing
     *
     * @phpstan-ignore-next-line
     */
    public function getId();

    public function getUserComUrl(): ?string;

    public function setUserComUrl(string $userComUrl): void;

    public function getUserComApiKey(): ?string;

    public function setUserComApiKey(?string $userComApiKey): void;

    public function getUserComGTMContainerId(): ?string;

    public function setUserComGTMContainerId(?string $userComGTMContainerId): void;
}
