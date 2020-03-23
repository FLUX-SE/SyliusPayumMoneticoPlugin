<?php


namespace Prometee\SyliusPayumMoneticoPlugin\Builder;


use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

class ContextBuilder implements BuilderInterface
{
    /** @var AddressBuilder */
    protected $addressBuilder;

    /**
     * ContextBuilder constructor.
     * @param AddressBuilder $addressBuilder
     */
    public function __construct(AddressBuilder $addressBuilder)
    {
        $this->addressBuilder = $addressBuilder;
    }

    public function build($payload)
    {
        Assert::isInstanceOf($payload, PaymentInterface::class);

        /** @var PaymentInterface $payload */

        $context = [];

        $billingAddress = $payload->getOrder()->getBillingAddress();
        $context['billing'] = $this->addressBuilder->build($billingAddress);

        $shippingAddress = $payload->getOrder()->getBillingAddress();
        $context['shipping'] = $this->addressBuilder->build($shippingAddress);

        return $context;
    }
}