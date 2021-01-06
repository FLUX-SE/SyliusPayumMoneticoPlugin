<?php

declare(strict_types=1);

namespace Prometee\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\AddressInterface;

final class AddressProvider implements AddressProviderInterface
{
    public function getAddress(AddressInterface $address): array
    {
        $result = [];

        $result['firstName'] = $address->getFirstName();
        $result['lastName'] = $address->getLastName();

        $street = $address->getStreet() ?? '';
        $result['address'] = $street;

        // addressLine* are required by `ekyna/payum-monetico`
        // but not by the Monetico documentation apparently
        $addressLines = $this->splitWords($street);
        $result['addressLine1'] = $addressLines[0];
        if (isset($addressLines[1])) {
            $result['addressLine2'] = $addressLines[1];
        }
        if (isset($addressLines[2])) {
            $result['addressLine3'] = $addressLines[2];
        }

        $result['city'] = $address->getCity();
        $result['postalCode'] = $address->getPostcode();
        $result['country'] = $address->getCountryCode();
        $result['stateOrProvince'] = $address->getProvinceCode() ?? $address->getProvinceName();

        return $result;
    }

    /** @return string[] */
    private function splitWords(string $text, int $width = 50): array
    {
        $words = explode(' ', $text);
        $text = '';
        $texts = [];

        foreach ($words as $word) {
            if (mb_strlen($text . ' ' . $word) > $width) {
                $texts[] = $text;
                $text = '';
            }

            $text .= ($text === '' ? '' : ' ') . $word;
        }

        $texts[] = $text;

        return $texts;
    }
}
