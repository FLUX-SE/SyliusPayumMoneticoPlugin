<?php

declare(strict_types=1);

namespace Prometee\SyliusPayumMoneticoPlugin\Provider;

use Sylius\Component\Core\Model\PaymentInterface;

interface CommentProviderInterface
{
    public function getComment(PaymentInterface $payment): string;
}
