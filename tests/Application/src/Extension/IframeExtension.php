<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\App\Extension;

use FluxSE\SyliusPayumMoneticoPlugin\Action\ConvertPaymentAction;
use Payum\Core\Extension\Context;
use Payum\Core\Extension\ExtensionInterface;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class IframeExtension implements ExtensionInterface
{
    public function onPreExecute(Context $context): void
    {
    }

    public function onExecute(Context $context): void
    {
    }

    public function onPostExecute(Context $context): void
    {
        $exception = $context->getException();
        if (null !== $exception) {
            return;
        }

        $action = $context->getAction();
        if (false === $action instanceof ConvertPaymentAction) {
            return;
        }

        $request = $context->getRequest();
        if (false === $request instanceof Convert) {
            return;
        }

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();
        Assert::isInstanceOf($payment, PaymentInterface::class);

        $order = $payment->getOrder();
        Assert::notNull($order);

        $channel = $order->getChannel();
        Assert::notNull($channel);

        $channelCode = $channel->getCode();
        Assert::notNull($channelCode);

        if ($this->needIframe($channelCode)) {
            /** @var array $model */
            $model = $request->getResult();

            $model['mode_affichage'] = 'iframe';

            $request->setResult($model);
        }
    }

    private function needIframe(string $channelCode): bool
    {
        // $this->parameterRepository->findByCodeAndChannelCode('monetico_iframe', $channelCode);
        return true;
    }
}
