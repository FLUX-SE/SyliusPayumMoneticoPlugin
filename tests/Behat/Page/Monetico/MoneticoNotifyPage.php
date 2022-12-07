<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Page\Monetico;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class MoneticoNotifyPage extends SymfonyPage implements MoneticoNotifyPageInterface
{
    public function getAbsoluteUrl(): string
    {
        return $this->getUrl();
    }

    public function getRouteName(): string
    {
        return 'flux_se_sylius_payum_monetico_notify';
    }
}
