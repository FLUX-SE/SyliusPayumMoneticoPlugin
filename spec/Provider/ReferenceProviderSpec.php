<?php

declare(strict_types=1);

namespace spec\Prometee\SyliusPayumMoneticoPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Prometee\SyliusPayumMoneticoPlugin\Provider\ReferenceProvider;
use Prometee\SyliusPayumMoneticoPlugin\Provider\ReferenceProviderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

class ReferenceProviderSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ReferenceProvider::class);
        $this->shouldHaveType(ReferenceProviderInterface::class);
    }

    public function it_get_address_from_an_address(
        PaymentInterface $payment
    ): void {
        $this->getReference($payment)->shouldReturn('SY' . time());
    }
}
