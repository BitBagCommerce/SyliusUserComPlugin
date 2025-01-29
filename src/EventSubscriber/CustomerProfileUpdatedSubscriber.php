<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\EventSubscriber;

use BitBag\SyliusUserComPlugin\Manager\CustomerUpdateManagerInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class CustomerProfileUpdatedSubscriber implements CustomerProfileUpdatedSubscriberInterface
{
    private const EVENTS = [
        FormEvents::POST_SUBMIT => 'postCustomerProfileUpdated',
    ];

    public function __construct(
        private readonly CustomerUpdateManagerInterface $customerUpdateManager,
    ) {
    }

    public function postCustomerProfileUpdated(FormEvent $event): void
    {
        $formName = $event->getForm()->getName();

        $eventName = array_key_exists($formName, self::FORM_NAME_TO_EVENT_MAP)
            ? self::FORM_NAME_TO_EVENT_MAP[$formName]
            : self::DEFAULT_EVENT
        ;

        $subject = $event->getData();

        if ($subject instanceof CustomerInterface) {
            $this->customerUpdateManager->manageChange(
                $eventName,
                $subject,
                $subject->getDefaultAddress(),
                $subject->getEmail(),
            );

            return;
        }

        if ($subject instanceof OrderInterface) {
            $customer = $subject->getCustomer();
            if (null !== $customer && !$customer instanceof CustomerInterface) {
                throw new \RuntimeException('Customer is not set or is not an instance of CustomerInterface');
            }
            $this->customerUpdateManager->manageChange(
                $eventName,
                $customer,
                $subject->getShippingAddress(),
                $customer?->getEmail(),
            );
        }
    }

    public static function getSubscribedEvents(): array
    {
        return self::EVENTS;
    }
}
