<?php

declare(strict_types=1);

namespace Prometee\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\PaymentInterface;

final class ReferenceProvider implements ReferenceProviderInterface
{
    public function getReference(PaymentInterface $payment): string
    {
        /**
         * This number should be uniq to allow payment errors to be renewable
         */
        return sprintf('SY%s',
            time()
        );
    }
}
