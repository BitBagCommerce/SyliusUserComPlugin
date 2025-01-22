<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusUserComPlugin\Entity;

use BitBag\SyliusUserComPlugin\Entity\UserComApiAwareTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

class Channel extends BaseChannel implements ChannelInterface
{
    use UserComApiAwareTrait;

    public function getId(): ?int
    {
        return $this->id;
    }
}
