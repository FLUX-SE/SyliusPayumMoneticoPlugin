<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Context\Setup\MoneticoContext;
use Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Page\External\MoneticoCheckoutPageInterface;

class MoneticoShopContext extends MinkContext implements Context
{
    /** @var CompletePageInterface */
    private $summaryPage;

    /** @var ShowPageInterface */
    private $orderDetails;

    /** @var MoneticoCheckoutPageInterface */
    private $paymentPage;

    public function __construct(
        CompletePageInterface $summaryPage,
        ShowPageInterface $orderDetails,
        MoneticoCheckoutPageInterface $paymentPage
    ) {
        $this->summaryPage = $summaryPage;
        $this->orderDetails = $orderDetails;
        $this->paymentPage = $paymentPage;
    }

    /**
     * @When I confirm my order with Monetico payment
     * @Given I have confirmed my order with Monetico payment
     */
    public function iConfirmMyOrderWithMoneticoPayment()
    {
        $this->summaryPage->confirmOrder();
    }

    /**
     * @When I get redirected to Monetico and complete my payment
     */
    public function iGetRedirectedToMonetico(): void
    {
        $postData = $this->getPostDataWithCodeRetour('paiement');
        $this->paymentPage->notify($postData);
        $this->paymentPage->capture();
    }

    /**
     * @Given I get redirected to Monetico and fail my attempt
     */
    public function IGetRedirectedToMoneticoAndFailMyAttempt(): void
    {
        $postData = $this->getPostDataWithCodeRetour('canceled');
        $this->paymentPage->notify($postData);
    }

    /**
     * @Given I retry my payment attempt and succeed
     */
    public function IRetryMyPaymentAttemptAndSucceed(): void
    {
        $postData = $this->getPostDataWithCodeRetour('paiement');
        $this->paymentPage->notify($postData);
    }

    /**
     * @Given I have clicked on "go back" during my Monetico payment
     * @When I click on "go back" during my Monetico payment
     * @When I get back from the Monetico portal
     */
    public function iCancelMyMoneticoPayment()
    {
        $this->paymentPage->capture();
    }

    /**
     * @When I try to pay again Monetico payment
     */
    public function iTryToPayAgainMoneticoPayment(): void
    {
        $this->orderDetails->pay();
    }

    private function getPostDataWithCodeRetour(string $codeRetour): array
    {
        return [
            'TPE' => MoneticoContext::TPE,
            'date' => '05/12/2006_a_11:55:23',
            'montant' => '19.99USD',
            'texte-libre' => 'LeTexteLibre',
            'code-retour' => $codeRetour,
            'cvx' => 'oui',
            'vld' => '1208',
            'brand' => 'VI',
            'status3ds' => '1',
            'numauto' => '010101',
            'originecb' => 'FRA',
            'bincb' => '010101',
            'hpancb' => '74E94B03C22D786E0F2C2CADBFC1C00B004B7C45', // found in the doc
            'ipclient' => '127.0.0.1',
            'originetr' => 'FRA',
            'veres' => 'Y',
            'pares' => 'Y',
            'authentification' => base64_encode(json_encode([ // found in the doc
                'status' => 'authenticated',
                'protocol' => '3DSecure',
                'version' => '2.1.0',
                'details' => [
                    'liabilityShift' => 'Y',
                    'ARes' => 'C',
                    'CRes' => 'Y',
                    'merchantPreference' => 'no_preference',
                    'transactionID' => '555bd9d9-1cf1-4ba8-b37c-1a96bc8b603a',
                    'authenticationValue' => 'cmJvd0I4SHk3UTRkYkFSQ3FYY3U=',
                ],
            ], JSON_THROW_ON_ERROR)),
        ];
    }
}
