<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Action;

use FluxSE\SyliusPayumMoneticoPlugin\Provider\CommentProviderInterface;
use FluxSE\SyliusPayumMoneticoPlugin\Provider\ContextProviderInterface;
use FluxSE\SyliusPayumMoneticoPlugin\Provider\ReferenceProviderInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Payum\Core\Request\GetCurrency;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class ConvertPaymentAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /** @var ContextProviderInterface */
    private $contextProvider;

    /** @var ReferenceProviderInterface */
    private $referenceProvider;

    /** @var CommentProviderInterface */
    private $commentProvider;

    public function __construct(
        ContextProviderInterface $contextProvider,
        ReferenceProviderInterface $referenceProvider,
        CommentProviderInterface $commentProvider
    ) {
        $this->contextProvider = $contextProvider;
        $this->referenceProvider = $referenceProvider;
        $this->commentProvider = $commentProvider;
    }

    /** @param Convert $request */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $model = ArrayObject::ensureArrayObject($payment->getDetails());

        if (false === $model->offsetExists('amount')) {
            $this->setAmount($model, $payment);
        }

        if (false === $model->offsetExists('currency')) {
            $model->offsetSet('currency', $payment->getCurrencyCode());
        }

        if (false === $model->offsetExists('reference')) {
            $model->offsetSet('reference', $this->referenceProvider->getReference($payment));
        }

        if (false === $model->offsetExists('comment')) {
            $model->offsetSet('comment', $this->commentProvider->getComment($payment));
        }

        if (false === $model->offsetExists('email')) {
            $this->setEmail($model, $payment);
        }

        if (false === $model->offsetExists('context')) {
            $model->offsetSet('context', $this->contextProvider->getContext($payment));
        }

        $request->setResult($model->getArrayCopy());
    }

    private function setAmount(ArrayObject $model, PaymentInterface $payment): void
    {
        $currencyCode = $payment->getCurrencyCode();
        Assert::notNull($currencyCode);
        $currency = new GetCurrency($currencyCode);
        $this->gateway->execute($currency);
        $amount = $payment->getAmount() ?? 0;

        if (0 < $currency->exp) {
            $divisor = 10 ** $currency->exp;

            $amount = (string) round($amount / $divisor, $currency->exp);
            if (false !== $pos = strpos($amount, '.')) {
                $amount = str_pad($amount, $pos + 1 + $currency->exp, '0', \STR_PAD_RIGHT);
            }
        }

        $model->offsetSet('amount', $amount);
    }

    private function setEmail(ArrayObject $model, PaymentInterface $payment): void
    {
        $order = $payment->getOrder();

        if (null === $order) {
            throw new LogicException('Payment order should not be null !');
        }

        $customer = $order->getCustomer();
        if (null !== $customer) {
            $model->offsetSet('email', $customer->getEmail());
        }
    }

    public function supports($request): bool
    {
        return $request instanceof Convert
            && $request->getSource() instanceof PaymentInterface
            && $request->getTo() == 'array';
    }
}
