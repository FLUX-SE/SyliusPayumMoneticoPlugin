<?php


namespace Prometee\SyliusPayumMoneticoPlugin\Builder;


use Sylius\Component\Core\Model\AddressInterface;

class AddressBuilder implements AddressBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function build(AddressInterface $address): array
    {
        $result = [];

        $result['firstName'] = $address->getFirstName();
        $result['lastName'] = $address->getLastName();
        $result['addressLine1'] = $address->getStreet();
        $result['city'] = $address->getCity();
        $result['postalCode'] = $address->getPostcode();
        $result['country'] = $address->getCountryCode();
        $result['stateOrProvince'] = $address->getProvinceCode();

        return $result;
    }
}