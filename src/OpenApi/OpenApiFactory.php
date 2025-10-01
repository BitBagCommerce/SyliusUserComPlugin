<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;

final class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated,
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $paths = $openApi->getPaths();

        $pathItem = new Model\PathItem(
            summary: 'Synchronize user agreements coming from User.com',
            post: new Model\Operation(
                operationId: 'bitbag_usercom_customer_agreements_post',
                tags: ['UserComAgreements'],
                responses: [
                    '200' => [
                        'description' => 'OK',
                        'content' => [
                            'text/plain' => [
                                'schema' => [
                                    'type' => 'string',
                                    'example' => 'OK',
                                ],
                            ],
                        ],
                    ],
                    '400' => ['description' => 'Invalid JSON payload'],
                    '401' => ['description' => 'Unauthorized'],
                    '404' => ['description' => 'Not found'],
                    '500' => ['description' => 'Internal server error'],
                ],
                parameters: [
                    new Model\Parameter(
                        name: 'X-User-Com-Signature',
                        in: 'header',
                        description: 'Request signature',
                        required: false,
                        schema: ['type' => 'string'],
                    ),
                ],
                requestBody: new Model\RequestBody(
                    description: 'User.com agreements payload',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['extra'],
                                'properties' => [
                                    'extra' => [
                                        'type' => 'object',
                                        'required' => ['email', 'agreements'],
                                        'properties' => [
                                            'email' => [
                                                'type' => 'string',
                                                'format' => 'email',
                                                'example' => 'john.doe@example.com',
                                            ],
                                            'agreements' => [
                                                'type' => 'object',
                                                'additionalProperties' => [
                                                    'type' => 'boolean',
                                                ],
                                                'example' => [
                                                    'email_agreement' => true,
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $paths->addPath('/user-com/customer-agreements', $pathItem);

        return $openApi;
    }
}
