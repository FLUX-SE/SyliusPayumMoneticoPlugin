<?php

declare(strict_types=1);

namespace spec\Prometee\SyliusPayumMoneticoPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Prometee\SyliusPayumMoneticoPlugin\Provider\AddressProviderInterface;
use Prometee\SyliusPayumMoneticoPlugin\Provider\ContextProvider;
use Prometee\SyliusPayumMoneticoPlugin\Provider\ContextProviderInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

class ContextProviderSpec extends ObjectBehavior
{
    public function let(AddressProviderInterface $addressBuilder): void
    {
        $this->beConstructedWith($addressBuilder);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ContextProvider::class);
        $this->shouldHaveType(ContextProviderInterface::class);
    }

    public function it_get_context_from_a_payment(
        PaymentInterface $payment,
        OrderInterface $order,
        AddressInterface $billingAddress,
        AddressInterface $shippingAddress,
        AddressProviderInterface $addressBuilder
    ): void {
        $payment->getOrder()->willReturn($order);

        $order->getBillingAddress()->willReturn($billingAddress);
        $order->getShippingAddress()->willReturn($shippingAddress);

        $addressBuilder->getAddress($billingAddress)->willReturn([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'address' => '1 Street of my owned wonderful house on the beach avenue '
                . 'and even longer street address of more length'
                . ', and even longer street address',
            'addressLine1' => '1 Street of my owned wonderful house on the beach',
            'addressLine2' => 'avenue and even longer street address of more',
            'addressLine3' => 'length, and even longer street address',
            'city' => 'A city',
            'postalCode' => '12345',
            'country' => 'FR',
            'stateOrProvince' => 'Île de France',
        ]);

        $addressBuilder->getAddress($shippingAddress)->willReturn([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'address' => '1 Street of my owned wonderful house on the beach avenue '
                . 'and even longer street address of more length'
                . ', and even longer street address',
            'addressLine1' => '1 Street of my owned wonderful house on the beach',
            'addressLine2' => 'avenue and even longer street address of more',
            'addressLine3' => 'length, and even longer street address',
            'city' => 'A city',
            'postalCode' => '12345',
            'country' => 'FR',
            'stateOrProvince' => 'Île de France',
        ]);

        $this->getContext($payment)->shouldReturn([
            'billing' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'address' => '1 Street of my owned wonderful house on the beach avenue '
                    . 'and even longer street address of more length'
                    . ', and even longer street address',
                'addressLine1' => '1 Street of my owned wonderful house on the beach',
                'addressLine2' => 'avenue and even longer street address of more',
                'addressLine3' => 'length, and even longer street address',
                'city' => 'A city',
                'postalCode' => '12345',
                'country' => 'FR',
                'stateOrProvince' => 'Île de France',
            ],
            'shipping' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'address' => '1 Street of my owned wonderful house on the beach avenue '
                    . 'and even longer street address of more length'
                    . ', and even longer street address',
                'addressLine1' => '1 Street of my owned wonderful house on the beach',
                'addressLine2' => 'avenue and even longer street address of more',
                'addressLine3' => 'length, and even longer street address',
                'city' => 'A city',
                'postalCode' => '12345',
                'country' => 'FR',
                'stateOrProvince' => 'Île de France',
            ],
        ]);
    }
}
