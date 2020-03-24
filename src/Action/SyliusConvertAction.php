<?php
/**
 * Created by PhpStorm.
 * User: prometee
 * Date: 16/10/2018
 * Time: 16:21
 */

namespace Prometee\SyliusPayumMoneticoPlugin\Action;


use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Payum\Core\Request\GetCurrency;
use Prometee\SyliusPayumMoneticoPlugin\Builder\ContextBuilder;
use Sylius\Component\Core\Model\PaymentInterface;

/**
 * Class SyliusConvertAction
 * @package Prometee\SyliusPayumMoneticoPlugin\Action
 */
class SyliusConvertAction implements ActionInterface, GatewayAwareInterface
{
    public const PAYMENT_ID_FORMAT = 'sylius%s';

    use GatewayAwareTrait;

    /** @var ContextBuilder */
    protected $contextBuilder;

    /**
     * SyliusConvertAction constructor.
     * @param ContextBuilder $contextBuilder
     */
    public function __construct(ContextBuilder $contextBuilder)
    {
        $this->contextBuilder = $contextBuilder;
    }

    /**
     * {@inheritDoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $model = ArrayObject::ensureArrayObject($payment->getDetails());

        //If there is a new token, clear all details
        if (isset($model['success_url']) && basename($model['success_url']) !== $request->getToken()->getHash()) {
            $model = new ArrayObject();
        }

        if (false == $model['amount']) {
            $this->setAmount($model, $payment);
        }

        if (false == $model['reference']) {
            $this->setReference($model, $payment);
        }

        if (false == $model['comment']) {
            $this->setComment($model, $payment);
        }

        if (false == $model['email']) {
            $this->setEmail($model, $payment);
        }

        if (false == $model['context']) {
            $this->setContext($model, $payment);
        }

        $request->setResult((array)$model);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof Convert
            && $request->getSource() instanceof PaymentInterface
            && $request->getTo() == 'array';
    }

    /**
     * @param ArrayObject $model
     * @param PaymentInterface $payment
     */
    protected function setAmount(ArrayObject $model, PaymentInterface $payment): void
    {
        $this->gateway->execute($currency = new GetCurrency($payment->getCurrencyCode()));
        $amount = (string)$payment->getAmount();

        if (0 < $currency->exp) {
            $divisor = pow(10, $currency->exp);
            $amount = (string)round($amount / $divisor, $currency->exp);

            if (false !== $pos = strpos($amount, '.')) {
                $amount = str_pad($amount, $pos + 1 + $currency->exp, '0', STR_PAD_RIGHT);
            }
        }

        $model['amount'] = $amount;
        $model['currency'] = (string)strtoupper($currency->code);
    }

    /**
     * @param ArrayObject $model
     * @param PaymentInterface $payment
     */
    protected function setReference(ArrayObject $model, PaymentInterface $payment): void
    {
        // The ID should be always unique so we can use it,
        // but we can also use Unix timestamp to get a really uniq value
//        $model['reference'] = sprintf(static::PAYMENT_ID_FORMAT, $payment->getId());
        $model['reference'] = substr(uniqid(), 0, 12);
    }

    /**
     * @param ArrayObject $model
     * @param PaymentInterface $payment
     */
    protected function setComment(ArrayObject $model, PaymentInterface $payment): void
    {
        $order = $payment->getOrder();
        $comment = "Order: {$order->getNumber()}";
        if (null !== $customer = $order->getCustomer()) {
            $comment .= ", Customer: {$customer->getId()}";
        }
        $model['comment'] = $comment;
    }

    /**
     * @param ArrayObject $model
     * @param PaymentInterface $payment
     */
    protected function setEmail(ArrayObject $model, PaymentInterface $payment): void
    {
        $order = $payment->getOrder();
        if (null !== $customer = $order->getCustomer()) {
            $model['email'] = $customer->getEmail();
        }
    }

    /**
     * @param ArrayObject $model
     * @param PaymentInterface $payment
     */
    protected function setContext(ArrayObject $model, PaymentInterface $payment): void
    {
        $model['context'] = $this->contextBuilder->build($payment);
    }
}
