<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Builder\Payload;

use Sylius\Component\Core\Model\ProductVariantInterface;

final class ProductEventPayloadBuilder implements ProductEventPayloadBuilderInterface
{
    public function build(string $eventType, ProductVariantInterface $variant, string $email): array
    {
        return [
            'custom_id' => $variant->getId(),
            'name' => $variant->getName(),
            'user_custom_id' => $email,
            'event_type' => $eventType,
        ];
    }
}
