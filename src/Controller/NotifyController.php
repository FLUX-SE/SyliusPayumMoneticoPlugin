<?php
/**
 * Created by PhpStorm.
 * User: prometee
 * Date: 21/10/2018
 * Time: 23:11
 */

namespace Prometee\SyliusPayumMoneticoPlugin\Controller;


use Ekyna\Component\Payum\Monetico\Api\Api;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Notify;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotifyController extends Controller
{
    public function index(Request $request) {
        // Get the reference you set in your ConvertAction
        if (null === $reference = $request->request->get('reference')) {
            throw new NotFoundHttpException();
        }

        // Find your payment entity
        $payment = $this
            ->get('sylius.repository.payment')
            ->find($reference);

        if (null === $payment) {
            throw new NotFoundHttpException();
        }

        $payum = $this->get('payum');

        // Execute notify & status actions.
        $gateway = $payum->getGateway('monetico');
        $gateway->execute(new Notify($payment));
        $gateway->execute(new GetHumanStatus($payment));

        // Get the payment identity
        $identity = $payum->getStorage($payment)->identify($payment);

        // Invalidate payment tokens
        $tokens = $payum->getTokenStorage()->findBy([
            'details' => $identity,
        ]);
        foreach ($tokens as $token) {
            $payum->getHttpRequestVerifier()->invalidate($token);
        }

        // Return expected response
        return new Response(Api::NOTIFY_SUCCESS);
    }
}
