<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Assigner;

use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class AgreementsAssigner implements AgreementsAssignerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function assign(CustomerInterface $customer, array $agreements): void
    {
        foreach ($agreements as $key => $value) {
            match ($key) {
                'email_agreement' => $customer->setSubscribedToNewsletter($value),
                default => $this->logger->error(
                    sprintf(
                        'Agreement not found. Key = %s, Value = %s, CustomerId = %s',
                        $key,
                        $value,
                        $customer->getId(),
                    ),
                ),
            };
        }
    }
}
