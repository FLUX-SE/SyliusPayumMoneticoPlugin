<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\PaymentMethod\CreatePage as BaseCreatePage;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function setMoneticoKey(string $key): void
    {
        $this->getDocument()->fillField('KEY', $key);
    }

    public function setMoneticoTpe(string $tpe): void
    {
        $this->getDocument()->fillField('TPE number', $tpe);
    }

    public function setMoneticoCompany(string $company): void
    {
        $this->getDocument()->fillField('COMPANY', $company);
    }

    public function selectMoneticoMode(string $mode): void
    {
        $this->getElement('monetico_mode')->selectOption($mode);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'monetico_mode' => 'input[name="sylius_payment_method[gatewayConfig][config][mode]"]',
        ]);
    }
}
