<?php

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin;

use BitBag\SyliusUserComPlugin\DependencyInjection\LogoutSubscriberPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BitBagSyliusUserComPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new LogoutSubscriberPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
