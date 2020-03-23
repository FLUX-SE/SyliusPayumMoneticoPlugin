<?php


namespace Prometee\SyliusPayumMoneticoPlugin\Builder;


use Sylius\Component\Core\Model\AddressInterface;
use Webmozart\Assert\Assert;

class AddressBuilder implements BuilderInterface
{
    public function build($payload)
    {
        Assert::isInstanceOf($payload, AddressInterface::class);

        /** @var AddressInterface $payload */

        $address = [];

        $address['firstName'] = $payload->getFirstName();
        $address['lastName'] = $payload->getLastName();
        $address['addressLine1'] = $payload->getStreet();
        $address['city'] = $payload->getCity();
        $address['postalCode'] = $payload->getPostcode();
        $address['country'] = $payload->getCountryCode();
        $address['stateOrProvince'] = $payload->getProvinceCode();

        return $address;
    }
}