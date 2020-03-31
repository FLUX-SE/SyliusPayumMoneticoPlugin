<?php


namespace Prometee\SyliusPayumMoneticoPlugin\Builder;


use Sylius\Component\Core\Model\PaymentInterface;

interface ContextBuilderInterface
{
    /**
     * @param PaymentInterface $payment
     * @return array
     */
    public function build(PaymentInterface $payment): array;
}