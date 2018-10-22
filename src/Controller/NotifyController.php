<?php
/**
 * Created by PhpStorm.
 * User: prometee
 * Date: 21/10/2018
 * Time: 23:11
 */

namespace Prometee\SyliusPayumMoneticoPlugin\Controller;


use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Ekyna\Component\Payum\Monetico\Api\Api;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Notify;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotifyController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        // Get the reference you set in your ConvertAction
        if (null === $reference = $request->request->get('reference')) {
            throw new NotFoundHttpException();
        }

        // Find your payment entity
        try {
            /** @var PaymentInterface $payment */
            $payment = $this
                ->get('sylius.repository.payment')
                ->createQueryBuilder('p')
                ->join('p.method', 'm')
                ->join('m.gatewayConfig', 'gc')
                ->where('p.details LIKE :reference')
                ->andWhere('gc.factoryName = :factory_name')
                ->setParameters([
                    'reference'=>'%"reference":"'.$reference.'"%',
                    'factory_name'=>'monetico'
                ])
                ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new NotFoundHttpException(
                sprintf('Payments not found for this reference : "%s" !', $reference),
                $e
            );
        } catch (NonUniqueResultException $e) {
            throw new NotFoundHttpException(
                sprintf('Many payments found for this reference : "%s", only one is required !', $reference),
                $e
            );
        }

        /** @var PaymentMethodInterface $payment_method */
        $payment_method = $payment->getMethod();
        $gateway_name = $payment_method->getGatewayConfig()->getGatewayName();

        $payum = $this->get('payum');

        // Execute notify & status actions.
        $gateway = $payum->getGateway($gateway_name);
        $gateway->execute(new Notify($payment));
        $gateway->execute(new GetHumanStatus($payment));

        // We don't invalidate payment tokens because if the customer click on go back to the store
        // the token will not exists anymore so there will be a 404 error page
        // let Sylius delete the token when it will be expired

        // Return expected response
        return new Response(Api::NOTIFY_SUCCESS);
    }
}
