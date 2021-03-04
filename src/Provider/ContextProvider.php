<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class ContextProvider implements ContextProviderInterface
{
    /** @var AddressProviderInterface */
    private $addressBuilder;

    public function __construct(AddressProviderInterface $addressBuilder)
    {
        $this->addressBuilder = $addressBuilder;
    }

    public function getContext(PaymentInterface $payment): array
    {
        $context = [];

        /** @var OrderInterface|null $order */
        $order = $payment->getOrder();
        Assert::notNull($order);

        $billingAddress = $order->getBillingAddress();
        Assert::notNull($billingAddress);
        $context['billing'] = $this->addressBuilder->getAddress($billingAddress);

        $shippingAddress = $order->getShippingAddress();
        Assert::notNull($shippingAddress);
        $context['shipping'] = $this->addressBuilder->getAddress($shippingAddress);

        return $context;
    }
}
