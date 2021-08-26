<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Provider;

use FluxSE\SyliusPayumMoneticoPlugin\Form\Type\MoneticoGatewayConfigurationType;
use Sylius\Component\Core\Model\PaymentInterface;

class ProtocolProvider implements ProtocolProviderInterface
{

    public function getProtocol(PaymentInterface $payment): ?string
    {
        $gatewayConfig = $payment->getMethod()->getGatewayConfig()->getConfig();
        if ($gatewayConfig && array_key_exists('protocol',$gatewayConfig) && $gatewayConfig['protocol']) {
            return $gatewayConfig['protocol'];
        }
        return null;

    }
    public function getDesactiveMoyenPaiement(PaymentInterface $payment): string
    {
        $protocoles = MoneticoGatewayConfigurationType::$protocoles;
        array_shift($protocoles);
        $protocoles = implode(',', array_flip($protocoles));
        return  $protocoles;

    }
}
