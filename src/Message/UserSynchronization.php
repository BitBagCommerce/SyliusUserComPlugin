<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Message;

final class UserSynchronization
{
    private string $eventName;

    private ?int $customerId = null;

    private ?int $addressId = null;

    private ?string $email = null;

    private ?string $userComCookie;

    private mixed $userComApiAwareResourceId;

    public function __construct(
        string $eventName,
        mixed $userComApiAwareResourceId,
        ?string $userComCookie = null,
        ?int $customerId = null,
        ?int $addressId = null,
        ?string $email = null,
    ) {
        $this->eventName = $eventName;
        $this->customerId = $customerId;
        $this->addressId = $addressId;
        $this->email = $email;
        $this->userComApiAwareResourceId = $userComApiAwareResourceId;
        $this->userComCookie = $userComCookie;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function getAddressId(): ?int
    {
        return $this->addressId;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getUserComApiAwareResourceId(): mixed
    {
        return $this->userComApiAwareResourceId;
    }

    public function getUserComCookie(): ?string
    {
        return $this->userComCookie;
    }
}
