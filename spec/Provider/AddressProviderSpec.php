<?php

declare(strict_types=1);

namespace spec\FluxSE\SyliusPayumMoneticoPlugin\Provider;

use PhpSpec\ObjectBehavior;
use FluxSE\SyliusPayumMoneticoPlugin\Provider\AddressProvider;
use FluxSE\SyliusPayumMoneticoPlugin\Provider\AddressProviderInterface;
use Sylius\Component\Core\Model\AddressInterface;

class AddressProviderSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(AddressProvider::class);
        $this->shouldHaveType(AddressProviderInterface::class);
    }

    public function it_get_address_from_an_address(
        AddressInterface $address
    ): void {
        $address->getFirstName()->willReturn('John');
        $address->getLastName()->willReturn('Doe');
        $address->getStreet()
            ->willReturn(
                '1 Street of my owned wonderful house on the beach avenue '
                . 'and even longer street address of more length'
                . ', and even longer street address'
            );
        $address->getCity()->willReturn('A city');
        $address->getPostcode()->willReturn('12345');
        $address->getCountryCode()->willReturn('FR');
        $address->getProvinceCode()->willReturn(null);
        $address->getProvinceName()->willReturn('Île de France');

        $this->getAddress($address)->shouldReturn([
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
    }
}
