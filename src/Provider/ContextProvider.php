<?php

declare(strict_types=1);

namespace Prometee\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class ContextProvider implements ContextProviderInterface
{
    /** @var AddressProviderInterface */
    private $addressBuilder;

    public function __construct(AddressProviderInterface $addressBuilder)
    {
        $this->addressBuilder = $addressBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(PaymentInterface $payment): array
    {
        $context = [];

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $billingAddress = $order->getBillingAddress();
        $context['billing'] = $this->addressBuilder->getAddress($billingAddress);

        $shippingAddress = $order->getShippingAddress();
        $context['shipping'] = $this->addressBuilder->getAddress($shippingAddress);

        return $context;
    }
}
