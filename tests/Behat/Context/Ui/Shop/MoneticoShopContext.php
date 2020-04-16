<?php

declare(strict_types=1);

namespace Tests\Prometee\SyliusPayumMoneticoPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Tests\Prometee\SyliusPayumMoneticoPlugin\Behat\Context\Setup\MoneticoContext;
use Tests\Prometee\SyliusPayumMoneticoPlugin\Behat\Page\External\MoneticoCheckoutPageInterface;

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
        $postData = [
            'TPE' => MoneticoContext::TPE,
            'date' => '05/12/2006_a_11:55:23',
            'montant' => '19.99USD',
            'texte-libre' => 'LeTexteLibre',
            'code-retour' => 'paiement',
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
            ])),
        ];

        $this->paymentPage->notify($postData);
        $this->paymentPage->capture();
    }

    /**
     * @When I cancel my Monetico payment
     * @When I click on "go back" during my Monetico payment
     * @Given I have cancelled Monetico payment
     */
    public function iCancelMyMoneticoPayment()
    {
        $postData = [
            'TPE' => MoneticoContext::TPE,
            'date' => '05/10/2011_a_15:33:06',
            'montant' => '19.99USD',
            'texte-libre' => 'Ceci est un test, ne pas tenir compte.',
            'code-retour' => 'Annulation',
            'cvx' => 'oui',
            'vld' => '0912',
            'brand' => 'MC',
            'status3ds' => '-1',
            'motifrefus' => 'filtrage',
            'originecb' => 'FRA',
            'bincb' => '513283',
            'hpancb' => '764AD24CFABBB818E8A7DC61D4D6B4B89EA837ED',
            'ipclient' => '10.45.166.76',
            'originetr' => 'inconnue',
            'veres' => '',
            'pares' => '',
            'filtragecause' => '4-',
            'filtragevaleur' => 'FRA',
        ];

        $this->paymentPage->notify($postData);
        $this->paymentPage->capture();
    }

    /**
     * @When I try to pay again Monetico payment
     */
    public function iTryToPayAgainMoneticoPayment(): void
    {
        $this->orderDetails->pay();
    }
}
