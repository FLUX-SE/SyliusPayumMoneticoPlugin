<?php

namespace Prometee\SyliusPayumMoneticoPlugin\Action;

use Ekyna\Component\Payum\Monetico\Request\PaymentForm;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Sync;

/**
 * Class SyliusCaptureAction
 * @package Prometee\SyliusPayumMoneticoPlugin\Action
 */
class SyliusCaptureAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if ($token = $request->getToken()) {
            // Done redirection urls
            $targetUrl = $token->getTargetUrl();
            foreach (['success_url', 'failure_url'] as $field) {
                if (false == $model[$field]) {
                    $model[$field] = $targetUrl;
                }
            }

            // Notify url is unique for all payment.
            // Create a dedicated controller to handle notification.
        }

        //On go capture, date is always unset, due to reset on convert action
        if (false == $model['date'] && ($model['success_url'] && $model['failure_url'])) {
            $this->gateway->execute(new PaymentForm($model));
        }
        //On return capture, check code-retour
        if ($model['date'] && $model['code-retour'] && $model['code-retour'] == 'Annulation') {
            $model['markAsFailed'] = true;
        }

        $this->gateway->execute(new Sync($model));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return $request instanceof Capture
            && $request->getModel() instanceof \ArrayAccess;
    }
}
