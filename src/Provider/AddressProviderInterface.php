<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\AddressInterface;

interface AddressProviderInterface
{
    public function getAddress(AddressInterface $address): array;
}
