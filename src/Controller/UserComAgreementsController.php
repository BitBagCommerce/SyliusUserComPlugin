<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Controller;

use BitBag\SyliusUserComPlugin\Authenticator\RequestAuthenticatorInterface;
use BitBag\SyliusUserComPlugin\Synchronizer\CustomerAgreementsSynchronizerInterface;
use BitBag\SyliusUserComPlugin\Validator\PayloadValidatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UserComAgreementsController
{
    public function __construct(
        private readonly RequestAuthenticatorInterface $requestAuthenticator,
        private readonly CustomerAgreementsSynchronizerInterface $customerAgreementsSynchronizer,
        private readonly PayloadValidatorInterface $payloadValidator,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        try {
            if (false === $this->requestAuthenticator->authenticate($request)) {
                return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
            }
            $payload = json_decode($request->getContent(), true);

            if (!is_array($payload) || [] === $payload) {
                return new JsonResponse('Invalid JSON payload', Response::HTTP_BAD_REQUEST);
            }

            $errors = $this->payloadValidator->validate($payload);
            if ([] !== $errors) {
                $this->logger->error('User.com - Payload validation failed.', $errors);

                return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
            }

            $this->customerAgreementsSynchronizer->synchronize($payload);

            return new Response('OK', Response::HTTP_OK);
        } catch (NotFoundHttpException $exception) {
            $this->logger->error('User.com - Customer not found');

            return new Response('Not found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            $this->logger->error('User.com - Internal server error : ' . $exception->getMessage());

            return new Response('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
