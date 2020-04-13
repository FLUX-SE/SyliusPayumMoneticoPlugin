<?php


namespace Prometee\SyliusPayumMoneticoPlugin\Builder;


use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

class ContextBuilder implements ContextBuilderInterface
{
    /** @var AddressBuilder */
    protected $addressBuilder;

    /**
     * ContextBuilder constructor.
     * @param AddressBuilderInterface $addressBuilder
     */
    public function __construct(AddressBuilderInterface $addressBuilder)
    {
        $this->addressBuilder = $addressBuilder;
    }

    /**
     * @inheritDoc
     */
    public function build(PaymentInterface $payment): array
    {
        $context = [];

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $billingAddress = $order->getBillingAddress();
        $context['billing'] = $this->addressBuilder->build($billingAddress);

        $shippingAddress = $order->getShippingAddress();
        $context['shipping'] = $this->addressBuilder->build($shippingAddress);

        return $context;
    }
}