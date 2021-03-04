<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\PaymentInterface;

interface ContextProviderInterface
{
    public function getContext(PaymentInterface $payment): array;
}
