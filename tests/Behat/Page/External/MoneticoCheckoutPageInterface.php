<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Page\External;

use FriendsOfBehat\PageObjectExtension\Page\PageInterface;

interface MoneticoCheckoutPageInterface extends PageInterface
{
    public function capture(): void;

    public function notify(array $postData): void;
}
