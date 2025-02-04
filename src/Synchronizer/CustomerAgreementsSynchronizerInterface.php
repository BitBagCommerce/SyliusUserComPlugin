<?php

declare(strict_types=1);

namespace BitBag\SyliusUserComPlugin\Synchronizer;

interface CustomerAgreementsSynchronizerInterface
{
    public function synchronize(array $payload): void;
}
