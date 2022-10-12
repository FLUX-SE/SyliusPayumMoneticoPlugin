<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Refund;

class RefundAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /** @param Refund $request */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($status = new GetHumanStatus($model));
        if ($status->isCaptured()) {
            $model['state_override'] = 'refunded';
        }
    }

    public function supports($request): bool
    {
        return $request instanceof Refund &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
