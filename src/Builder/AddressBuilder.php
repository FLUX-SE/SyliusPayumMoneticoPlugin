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

        $result['firstName'] = substr($address->getFirstName(), 0, 45);
        $result['lastName'] = substr($address->getLastName(),0,45);
        $result['addressLine1'] = substr($address->getStreet(), 0, 50);
        $result['city'] = substr($address->getCity(), 0, 50);
        $result['postalCode'] = substr($address->getPostcode(), 0, 10);
        $result['country'] = $address->getCountryCode();
        $result['stateOrProvince'] = $address->getProvinceCode();

        return $result;
    }
}