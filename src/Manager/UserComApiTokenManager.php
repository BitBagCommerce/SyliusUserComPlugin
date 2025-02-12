<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Manager;

final class UserComApiTokenManager implements UserComApiTokenManagerInterface
{
    public function __construct(
        private readonly string $encryptionKey,
        private readonly string $encryptionIv,
    ) {
    }

    public function encrypt(string $token): string
    {
        $value = openssl_encrypt(
            $token,
            'aes-256-cbc',
            $this->encryptionKey,
            0,
            $this->encryptionIv,
        );

        if (false === $value) {
            throw new \RuntimeException('Could not encrypt the token');
        }

        return $value;
    }

    public function decrypt(string $encryptedToken): string
    {
        $value = openssl_decrypt(
            $encryptedToken,
            'aes-256-cbc',
            $this->encryptionKey,
            0,
            $this->encryptionIv,
        );

        if (false === $value) {
            throw new \RuntimeException('Could not decrypt the token');
        }

        return $value;
    }
}
