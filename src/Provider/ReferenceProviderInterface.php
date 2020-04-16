<?php

declare(strict_types=1);

namespace Prometee\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\PaymentInterface;

interface ReferenceProviderInterface
{
    public function getReference(PaymentInterface $payment): string;
}
