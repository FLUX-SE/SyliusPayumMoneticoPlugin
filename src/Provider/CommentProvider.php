<?php

declare(strict_types=1);

namespace Prometee\SyliusPayumMoneticoPlugin\Provider;

use LogicException;
use Sylius\Component\Core\Model\PaymentInterface;

class CommentProvider implements CommentProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getComment(PaymentInterface $payment): string
    {
        $order = $payment->getOrder();

        if (null === $order) {
            throw new LogicException('Payment order should not be null !');
        }

        $customerComment = '';
        $customer = $order->getCustomer();
        if (null !== $customer) {
            $customerComment = sprintf(', Customer: %s', $customer->getId());
        }

        $userComment = '';
        $user = $order->getUser();
        if (null !== $user) {
            $customerComment = sprintf(', User: %s', $user->getId());
        }

        return sprintf('Order: %s%s%s',
            $order->getNumber(),
            $customerComment,
            $userComment
        );
    }
}
