<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Extension;

use ArrayAccess;
use Ekyna\Component\Payum\Monetico\Action\Api\PaymentResponseAction;
use Ekyna\Component\Payum\Monetico\Request\PaymentResponse;
use Payum\Core\Extension\Context;
use Payum\Core\Extension\ExtensionInterface;
use Payum\Core\Request\Cancel;
use Payum\Core\Request\GetHttpRequest;

class PaymentResponseExtension implements ExtensionInterface
{
    public function onPreExecute(Context $context): void
    {
    }

    public function onExecute(Context $context): void
    {
    }

    public function onPostExecute(Context $context): void
    {
        if (null !== $context->getException()) {
            return;
        }

        $request = $context->getRequest();
        if (false === $request instanceof PaymentResponse) {
            return;
        }

        $action = $context->getAction();
        if (false === $action instanceof PaymentResponseAction) {
            return;
        }

        $model = $request->getModel();
        if (false === $model instanceof ArrayAccess) {
            return;
        }

        $gateway = $context->getGateway();

        $httpRequest = new GetHttpRequest();
        $gateway->execute($httpRequest);

        if (isset($httpRequest->request['code-retour'])) {
            return;
        }

        if (isset($httpRequest->query['code-retour'])) {
            return;
        }

        $gateway->execute(new Cancel($model));
    }
}
