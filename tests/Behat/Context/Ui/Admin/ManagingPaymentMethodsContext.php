<?php

declare(strict_types=1);

namespace Tests\Prometee\SyliusPayumMoneticoPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Ekyna\Component\Payum\Monetico\Api\Api;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Tests\Prometee\SyliusPayumMoneticoPlugin\Behat\Context\Setup\MoneticoContext;
use Tests\Prometee\SyliusPayumMoneticoPlugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;

class ManagingPaymentMethodsContext implements Context
{
    /** @var CreatePageInterface */
    private $createPage;

    public function __construct(CreatePageInterface $createPage)
    {
        $this->createPage = $createPage;
    }

    /**
     * @Given /^I want to create a new Monetico payment method$/
     *
     * @throws UnexpectedPageException
     */
    public function iWantToCreateANewMoneticoPaymentMethod(): void
    {
        $this->createPage->open(['factory' => 'monetico']);
    }

    /**
     * @When I configure it with test monetico gateway data
     */
    public function iConfigureItWithTestMoneticoGatewayData()
    {
        $this->createPage->setMoneticoKey(MoneticoContext::KEY);
        $this->createPage->setMoneticoTpe(MoneticoContext::TPE);
        $this->createPage->setMoneticoCompany(MoneticoContext::COMPANY);
        $this->createPage->selectMoneticoMode(Api::MODE_TEST);
    }
}
