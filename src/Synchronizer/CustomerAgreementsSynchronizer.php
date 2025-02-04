<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Synchronizer;

use BitBag\SyliusUserComPlugin\Assigner\AgreementsAssignerInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

final class CustomerAgreementsSynchronizer implements CustomerAgreementsSynchronizerInterface
{
    public function __construct(
        private readonly RepositoryInterface $customerRepository,
        private readonly AgreementsAssignerInterface $agreementsAssigner,
    ) {
    }

    public function synchronize(array $payload): void
    {
        $extra = $payload['extra'];
        $email = $extra['email'];
        $agreements = $extra['agreements'];

        Assert::isInstanceOf($this->customerRepository, CustomerRepositoryInterface::class);
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        if (null === $customer) {
            throw new NotFoundHttpException('Customer with given email was not found.');
        }

        Assert::isInstanceOf($customer, CustomerInterface::class);
        $this->agreementsAssigner->assign($customer, $agreements);
        $this->customerRepository->add($customer);
    }
}
