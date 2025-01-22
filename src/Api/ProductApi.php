<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Api;

use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Symfony\Component\HttpFoundation\Request;

final class ProductApi extends AbstractClient implements ProductApiInterface
{
    public const GET_PRODUCT_BY_CUSTOM_ID_ENDPOINT = '/products-by-id/%s/product_event/';

    public function getProductByCustomId(UserComApiAwareInterface $resource, int $productId): ?array
    {
        $url = $this->getApiEndpointUrl($resource, sprintf(self::GET_PRODUCT_BY_CUSTOM_ID_ENDPOINT, $productId));

        return $this->request(
            $url,
            Request::METHOD_GET,
            $this->buildOptions($resource),
        );
    }
}
