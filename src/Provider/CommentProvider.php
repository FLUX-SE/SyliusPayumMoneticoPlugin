<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Provider;

use LogicException;
use Sylius\Component\Core\Model\PaymentInterface;

class CommentProvider implements CommentProviderInterface
{
    public function getComment(PaymentInterface $payment): string
    {
        $order = $payment->getOrder();

        if (null === $order) {
            throw new LogicException('Payment order should not be null !');
        }

        $customerComment = '';
        $customer = $order->getCustomer();
        if (null !== $customer) {
            $customerId = (int) $customer->getId();
            $customerComment = sprintf(', Customer: %s', $customerId);
        }

        $userComment = '';
        $user = $order->getUser();
        if (null !== $user) {
            $userId = (int) $user->getId();
            $userComment = sprintf(', User: %s', $userId);
        }

        $orderNumber = $order->getNumber() ?? '';

        return sprintf('Order: %s%s%s',
            $orderNumber,
            $customerComment,
            $userComment
        );
    }
}
