<?php

namespace Prometee\SyliusPayumMoneticoPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

/**
 * Class SyliusStatusAction
 * @package Prometee\SyliusPayumMoneticoPlugin\Action
 */
class SyliusStatusAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (false == $code = $model['code-retour']) {
            if (false != $code = $model['state_override']) {
                if ($code === 'canceled') {
                    $request->markCanceled();

                    return;
                }
            }

            $request->markNew();

            return;
        }

        //If we are on return capture, and payment was mark as failed, inform Sylius
        if ($model['markAsFailed'] == true) {
            $request->markFailed();
        }
        else {
            switch ($code) {
                case "payetest" : // test payment acceptec
                case "paiement" : // prod payment acceptec
                    $request->markCaptured();
                    break;
                //If fail, mark as new to avoid Sylius payment state change before return
                default:
                    $request->markNew();
            }
        }

        if ($request->isCaptured() && false != $code = $model['state_override']) {
            if ($code === 'refunded') {
                $request->markRefunded();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return $request instanceof GetStatusInterface
            && $request->getModel() instanceof \ArrayAccess;
    }
}
