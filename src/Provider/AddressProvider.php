<?php

declare(strict_types=1);

namespace Prometee\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\AddressInterface;

final class AddressProvider implements AddressProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAddress(AddressInterface $address): array
    {
        $result = [];

        $result['firstName'] = $address->getFirstName();
        $result['lastName'] = $address->getLastName();
        $result['addressLine1'] = $address->getStreet();
        $result['city'] = $address->getCity();
        $result['postalCode'] = $address->getPostcode();
        $result['country'] = $address->getCountryCode();
        $result['stateOrProvince'] = $address->getProvinceCode() ?? $address->getProvinceName();

        return $result;
    }

    /**
     * @return string[]
     */
    private function splitWords(string $text, int $width): array
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
