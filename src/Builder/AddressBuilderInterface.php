<?php


namespace Prometee\SyliusPayumMoneticoPlugin\Builder;


use Sylius\Component\Core\Model\AddressInterface;

interface AddressBuilderInterface
{
    /**
     * @param AddressInterface $address
     * @return array
     */
    public function build(AddressInterface $address): array;
}