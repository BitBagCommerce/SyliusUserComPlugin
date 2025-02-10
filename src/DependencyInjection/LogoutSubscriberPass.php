<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\DependencyInjection;

use BitBag\SyliusUserComPlugin\Security\Handler\LogoutEventAddHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Webmozart\Assert\Assert;

final class LogoutSubscriberPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('sylius_shop.firewall_context_name')) {
            return;
        }

        $firewallName = $container->getParameter('sylius_shop.firewall_context_name');
        Assert::string($firewallName);
        $securityDispatcherId = sprintf('security.event_dispatcher.%s', $firewallName);

        if (!$container->hasDefinition($securityDispatcherId)) {
            return;
        }

        $logoutListener = new Definition(LogoutEventAddHandler::class, [
            new Reference('Spinbits\SyliusGoogleAnalytics4Plugin\Storage\EventsBag'),
        ]);

        $logoutListener->addTag('kernel.event_listener', [
            'event' => LogoutEvent::class,
            'dispatcher' => $securityDispatcherId,
            'method' => 'onLogout',
        ]);
        $logoutListener->setPublic(true);

        $container->setDefinition('bitbag.handler.user_logout_add_event', $logoutListener);
    }
}
