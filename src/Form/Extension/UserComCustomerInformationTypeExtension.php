<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Form\Extension;

use BitBag\SyliusUserComPlugin\EventSubscriber\CustomerProfileUpdatedSubscriber;
use BitBag\SyliusUserComPlugin\Manager\CookieManagerInterface;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType;
use Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerRegistrationType;
use Sylius\Bundle\CustomerBundle\Form\Type\CustomerProfileType;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class UserComCustomerInformationTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly CustomerProfileUpdatedSubscriber $customerProfileUpdatedSubscriber,
        private readonly CookieManagerInterface $cookieManager,
        private readonly CustomerContextInterface $customerContext,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this->customerProfileUpdatedSubscriber);

        if (
            false === $builder->has('customer') &&
            false === $builder->has('email') &&
            null === $this->customerContext->getCustomer()
        ) {
            $builder->add('email', TextType::class, [
                'mapped' => false,
            ]);
        }
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            CustomerRegistrationType::class,
            AddressType::class,
            CustomerProfileType::class,
        ];
    }
}
