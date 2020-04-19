<?php

declare(strict_types=1);

namespace spec\Prometee\SyliusPayumMoneticoPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Prometee\SyliusPayumMoneticoPlugin\Provider\CommentProvider;
use Prometee\SyliusPayumMoneticoPlugin\Provider\CommentProviderInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\User\Model\UserInterface;

class CommentProviderSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CommentProvider::class);
        $this->shouldHaveType(CommentProviderInterface::class);
    }

    public function it_get_comment_from_a_payment(
        PaymentInterface $payment,
        OrderInterface $order,
        UserInterface $user
    ): void {
        $payment->getOrder()->willReturn($order);

        $order->getCustomer()->willReturn(null);
        $order->getUser()->willReturn($user);
        $order->getNumber()->willReturn('SY00001');

        $user->getId()->willReturn(1);

        $this->getComment($payment)->shouldReturn('Order: SY00001, User: 1');
    }

    public function it_get_comment_from_a_payment_having_a_customer(
        PaymentInterface $payment,
        OrderInterface $order,
        CustomerInterface $customer,
        UserInterface $user
    ): void {
        $payment->getOrder()->willReturn($order);

        $order->getCustomer()->willReturn($customer);
        $order->getUser()->willReturn($user);
        $order->getNumber()->willReturn('SY00001');

        $customer->getId()->willReturn(1);

        $user->getId()->willReturn(1);

        $this->getComment($payment)->shouldReturn('Order: SY00001, Customer: 1, User: 1');
    }
}
