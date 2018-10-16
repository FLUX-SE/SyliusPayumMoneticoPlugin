<?php
/**
 * Created by PhpStorm.
 * User: prometee
 * Date: 16/10/2018
 * Time: 16:21
 */

namespace Prometee\SyliusPayumMoneticoPlugin\Action;


use Sylius\Component\Core\Model\PaymentInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Payum\Core\Request\GetCurrency;

/**
 * Class SyliusConvertAction
 * @package Prometee\SyliusPayumMoneticoPlugin\Action
 */
class SyliusConvertAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

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

        if (false == $model['amount']) {
            $this->gateway->execute($currency = new GetCurrency($payment->getCurrencyCode()));

            $amount = (string)round($payment->getAmount(), $currency->exp);

            if (0 < $currency->exp && false !== $pos = strpos($amount, '.')) {
                $amount = str_pad($amount, $pos + 1 + $currency->exp, '0', STR_PAD_RIGHT);
            }

            if (substr($amount, strrpos($amount, '.')) == 0) {
                $amount = (string)round($amount);
            }

            $model['currency'] = (string)$currency->alpha3;
            $model['amount'] = $amount;
        }

        $order = $payment->getOrder();

        if (false == $model['reference']) {
            $model['reference'] = (string)$payment->getId();
        }

        if (false == $model['comment']) {
            $comment = "Order: {$order->getNumber()}";
            if (null !== $customer = $order->getCustomer()) {
                $comment .= "\n"."Customer: {$customer->getId()}";
            }
            $model['comment'] = $comment;
        }

        if (false == $model['email']) {
            if (null !== $customer = $order->getCustomer()) {
                $model['email'] = $customer->getEmail();
            }
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
}
