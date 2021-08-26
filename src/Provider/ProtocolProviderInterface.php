<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\PaymentInterface;

interface ProtocolProviderInterface
{
    public function getProtocol(PaymentInterface $payment): ?string;
    
    public function getDesactiveMoyenPaiement(PaymentInterface $payment): string;
}
