<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Validator;

final class PayloadValidator implements PayloadValidatorInterface
{
    private const AGREEMENTS = 'agreements';

    private const EMAIL = 'email';

    public function validate(array $payload): array
    {
        $errors = [];
        if (!isset($payload['extra'])) {
            $errors[] = 'Extra is required.';

            return $errors;
        }

        $extra = $payload['extra'];

        if (!isset($extra[self::EMAIL])) {
            $errors[] = 'Email is required.';
        }

        $email = $extra[self::EMAIL];

        if (!is_string($email) || false === filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is not valid.';
        }

        if (false === is_array($extra[self::AGREEMENTS]) ||
            [] === $extra[self::AGREEMENTS]
        ) {
            $errors[] = 'Agreements are required.';
        }

        foreach ($extra[self::AGREEMENTS] as $agreement => $value) {
            if (!isset($agreement) || !is_string($agreement)) {
                $errors[] = 'Agreement code is required and must be a string.';
            }

            if (false === is_bool($value)) {
                $errors[] = 'Agreement value is required and must be a boolean.';
            }
        }

        return $errors;
    }
}
