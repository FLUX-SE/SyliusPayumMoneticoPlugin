[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Quality Score][ico-code-quality]][link-code-quality]

## Sylius Payum Monetico gateway plugin

This plugin is designed to add a new gateway to Payum to support Monetico over Sylius plugin

See https://www.monetico-paiement.fr/ for more information.

## Installation

Install using Composer :

```
$ composer require flux-se/sylius-payum-monetico-plugin
```

Enable this plugin :

```php
<?php

# config/bundles.php

return [
    // ...
    FluxSE\SyliusPayumMoneticoPlugin\FluxSESyliusPayumMoneticoPlugin::class => ['all' => true],
    // ...
];
```

Enable the required route for Monetico notify :

```yaml
# config/routes/flux_se_sylius_payum_monetico.yaml

flux_se_sylius_payum_monetico_notify:
  resource: "@FluxSESyliusPayumMoneticoPlugin/Resources/config/routing/notify.yaml"
```

## Configuration

### Monetico notify URL

Contact Monetico to add a "notify URL" corresponding to the route named `flux_se_sylius_payum_monetico_notify` example :

```
https://my_domain.tld/monetico/notify
``` 

### Monetico credentials

Get your `TPE number`, your `KEY` and your `COMPANY` name on your Monetico merchant portal :

https://www.monetico-services.com/fr/test/identification/authentification.html

Then click on the "Paramétrage" menu item, and finally on the sub menu item named "CLÉ DE SÉCURITÉ".
You will be able to send an email to the owner of the account to get your credentials.

**TIPS: `TPE number` can be chosen into the select menu in the right sidebar of your Monetico merchant portal**

### Sylius configuration

Go to the admin area, log in, then click on the left menu item "CONFIGURATION > Payment methods".
Create a new payment method type "Monetico" :

![Create a new payment method][docs-assets-create-payment-method]

Then a form will be displayed, fill-in the required fields :

 1. the "code" field (ex: "monetico").
 2. choose which channels this payment method will be affected to.
 3. the gateway configuration ([need info from here](#monetico-credentials)) :
 
    ![Gateway Configuration][docs-assets-gateway-configuration]
    
    _NOTE1: the screenshot contains false test credentials._
 4. give to this payment method a display name (and a description) for each language you need
 
 Finally, click on the "Create" button to save your new payment method.

[docs-assets-create-payment-method]: docs/assets/create-payment-method.png
[docs-assets-gateway-configuration]: docs/assets/gateway-configuration.png

[ico-version]: https://img.shields.io/packagist/v/FLUX-SE/sylius-payum-monetico-plugin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-github-actions]: https://github.com/FLUX-SE/SyliusPayumMoneticoPlugin/workflows/Build/badge.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/FLUX-SE/SyliusPayumMoneticoPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/flux-se/sylius-payum-monetico-plugin
[link-github-actions]: https://github.com/FLUX-SE/SyliusPayumMoneticoPlugin/actions?query=workflow%3A"Build"
[link-scrutinizer]: https://scrutinizer-ci.com/g/FLUX-SE/SyliusPayumMoneticoPlugin/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/FLUX-SE/SyliusPayumMoneticoPlugin
