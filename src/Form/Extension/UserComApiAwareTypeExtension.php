<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Form\Extension;

use BitBag\SyliusUserComPlugin\Trait\UserComApiAwareInterface;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

final class UserComApiAwareTypeExtension extends AbstractTypeExtension
{
    private const USER_COM_API_KEY_PROPERTY = 'userComApiKey';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'userComApiKey',
                PasswordType::class,
                [
                    'label' => 'bitbag_sylius_user_com_plugin.ui.user_com_api_key',
                    'required' => false,
                    'mapped' => false,
                ],
            )
            ->add(
                'userComUrl',
                TextType::class,
                [
                    'label' => 'bitbag_sylius_user_com_plugin.ui.user_com_url',
                    'required' => false,
                    'constraints' => [
                        new NotBlank(['groups' => ['sylius']]),
                        new Url(['groups' => ['sylius']]),
                    ],
                ],
            )
            ->addEventListener(FormEvents::SUBMIT, function (SubmitEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                $newPassword = $form->get(self::USER_COM_API_KEY_PROPERTY)->getData();

                if (
                    null !== $newPassword &&
                    is_string($newPassword) &&
                    $data instanceof UserComApiAwareInterface
                ) {
                    $data->setUserComApiKey($newPassword);
                }
            });
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            ChannelType::class,
        ];
    }
}
