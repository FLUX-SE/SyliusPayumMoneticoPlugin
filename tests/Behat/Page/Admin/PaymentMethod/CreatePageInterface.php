<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\PaymentMethod\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function setMoneticoKey(string $key): void;

    public function setMoneticoTpe(string $tpe): void;

    public function setMoneticoCompany(string $company): void;

    public function selectMoneticoMode(string $mode): void;
}
