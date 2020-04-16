<?php

declare(strict_types=1);

namespace Tests\Prometee\SyliusPayumMoneticoPlugin\Behat\Page\Monetico;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class MoneticoNotifyPage extends SymfonyPage implements MoneticoNotifyPageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAbsoluteUrl(): string
    {
        return $this->getUrl();
    }

    public function getRouteName(): string
    {
        return 'prometee_sylius_payum_monetico_notify';
    }
}
