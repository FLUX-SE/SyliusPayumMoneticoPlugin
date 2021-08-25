<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Controller;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Ekyna\Component\Payum\Monetico\Api\Api;
use Payum\Core\Exception\LogicException;
use Payum\Core\Payum;
use Payum\Core\Request\Notify;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NotifyController
{
    /** @var EntityRepository */
    private $paymentRepository;

    /** @var Payum */
    private $payum;

    public function __construct(
        EntityRepository $paymentRepository,
        Payum            $payum
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->payum = $payum;
    }

    public function doAction(Request $request): Response
    {
        // Get the reference you set in your ConvertAction
        $reference = $request->request->get('reference');

        if (null === $reference) {
            throw new NotFoundHttpException();
        }

        if (false === is_string($reference)) {
            throw new NotFoundHttpException();
        }

        $queryBuilder = $this->paymentRepository->createQueryBuilder('p');
        // Find your payment entity
        try {
            /** @var PaymentInterface $payment */
            $payment = $queryBuilder
                ->join('p.method', 'm')
                ->join('m.gatewayConfig', 'gc')
                ->where('p.details LIKE :reference')
                ->andWhere('gc.factoryName = :factory_name')
                ->andWhere('p.state = :state')
                ->setParameters([
                    'reference' => '%"reference":%"' . $reference . '"%',
                    'factory_name' => 'monetico',
                    'state' => PaymentInterface::STATE_NEW,
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
        $gatewayConfig = $payment_method->getGatewayConfig();

        if (null === $gatewayConfig) {
            throw new LogicException('The gateway config should not be nul !');
        }

        $gateway_name = $gatewayConfig->getGatewayName();

        // Execute notify & status actions.
        $gateway = $this->payum->getGateway($gateway_name);

        $gateway->execute(new Notify($payment));

        // We don't invalidate payment tokens because if the customer click on go back to the store
        // the token will not exists anymore so there will be a 404 error page

        // Return expected response
        return new Response(Api::NOTIFY_SUCCESS);
    }
}
