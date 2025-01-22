<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusUserComPlugin\Entity;

use BitBag\SyliusUserComPlugin\Entity\UserComApiAwareInterface;
use Sylius\Component\Core\Model\ChannelInterface as BaseChannelInterface;

interface ChannelInterface extends BaseChannelInterface, UserComApiAwareInterface
{
}
