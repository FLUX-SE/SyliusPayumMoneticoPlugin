<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Component\Payum\Monetico\Api\Api;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Webmozart\Assert\Assert;

class MoneticoContext implements Context
{
    public const TPE = '1234567';

    public const KEY = '01234567890ABCDEF01234567890ABCDEF012345';

    public const COMPANY = 'my_company';

    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var PaymentMethodRepositoryInterface */
    private $paymentMethodRepository;

    /** @var ExampleFactoryInterface */
    private $paymentMethodExampleFactory;

    /** @var EntityManagerInterface */
    private $paymentMethodManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ExampleFactoryInterface $paymentMethodExampleFactory,
        EntityManagerInterface $paymentMethodManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodExampleFactory = $paymentMethodExampleFactory;
        $this->paymentMethodManager = $paymentMethodManager;
    }

    /**
     * @Given the store has a payment method :paymentMethodName with a code :paymentMethodCode and Monetico payment gateway
     */
    public function theStoreHasAPaymentMethodWithACodeAndMoneticoPaymentGateway(
        string $paymentMethodName,
        string $paymentMethodCode
    ): void {
        $paymentMethod = $this->createPaymentMethodMonetico(
            $paymentMethodName,
            $paymentMethodCode,
            'monetico',
            'Monetico'
        );
        $gatewayConfig = $paymentMethod->getGatewayConfig();
        Assert::notNull($gatewayConfig);
        $gatewayConfig->setConfig([
            'key' => self::KEY,
            'tpe' => self::TPE,
            'company' => self::COMPANY,
            'mode' => Api::MODE_TEST,
        ]);
        $this->paymentMethodManager->flush();
    }

    private function createPaymentMethodMonetico(
        string $name,
        string $code,
        string $factoryName,
        string $description = '',
        bool $addForCurrentChannel = true,
        int $position = null
    ): PaymentMethodInterface {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $this->paymentMethodExampleFactory->create([
            'name' => ucfirst($name),
            'code' => $code,
            'description' => $description,
            'gatewayName' => $factoryName,
            'gatewayFactory' => $factoryName,
            'enabled' => true,
            'channels' => ($addForCurrentChannel && $this->sharedStorage->has('channel')) ? [$this->sharedStorage->get('channel')] : [],
        ]);
        if (null !== $position) {
            $paymentMethod->setPosition($position);
        }
        $this->sharedStorage->set('payment_method', $paymentMethod);
        $this->paymentMethodRepository->add($paymentMethod);

        return $paymentMethod;
    }
}
