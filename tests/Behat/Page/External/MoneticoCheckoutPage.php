<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Page\External;

use Behat\Mink\Session;
use Ekyna\Component\Payum\Monetico\Api\Api;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use LogicException;
use Payum\Core\Security\TokenInterface;
use RuntimeException;
use Sylius\Bundle\PayumBundle\Model\PaymentSecurityTokenInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpFoundation\Response;
use Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Context\Setup\MoneticoContext;
use Tests\FluxSE\SyliusPayumMoneticoPlugin\Behat\Page\Monetico\MoneticoNotifyPageInterface;

final class MoneticoCheckoutPage extends Page implements MoneticoCheckoutPageInterface
{
    /** @var RepositoryInterface<PaymentSecurityTokenInterface> */
    private $securityTokenRepository;

    /** @var HttpBrowser */
    private $httpBrowser;

    /** @var MoneticoNotifyPageInterface */
    private $moneticoNotifyPage;

    /** @var RepositoryInterface<PaymentInterface> */
    private $paymentRepository;

    /**
     * @param RepositoryInterface<PaymentSecurityTokenInterface> $securityTokenRepository
     * @param RepositoryInterface<PaymentInterface> $paymentRepository
     */
     public function __construct(
        Session $session,
        $minkParameters,
        RepositoryInterface $securityTokenRepository,
        HttpBrowser $httpBrowser,
        MoneticoNotifyPageInterface $moneticoNotifyPage,
        RepositoryInterface $paymentRepository
    ) {
        parent::__construct($session, $minkParameters);

        $this->securityTokenRepository = $securityTokenRepository;
        $this->httpBrowser = $httpBrowser;
        $this->moneticoNotifyPage = $moneticoNotifyPage;
        $this->paymentRepository = $paymentRepository;
    }

    public function capture(): void
    {
        $captureToken = $this->findToken(false);

        $this->getDriver()->visit($captureToken->getTargetUrl());
    }

    public function notify(array $postData): void
    {
        $api = new Api();
        $api->setConfig([
            'key' => MoneticoContext::KEY,
            'tpe' => MoneticoContext::TPE,
            'company' => MoneticoContext::COMPANY,
            'mode' => Api::MODE_TEST,
        ]);

        $captureToken = $this->findToken(false);
        $postData['reference'] = $this->retrieveReference($captureToken);

        ksort($postData);
        $postData['MAC'] = $api->computeMac($postData);

        $this->httpBrowser->request('POST', $this->moneticoNotifyPage->getAbsoluteUrl(), $postData);
        /** @var Response $response */
        $response = $this->httpBrowser->getResponse();
        if ($response->getStatusCode() !== 200) {
            throw new LogicException('Notify Request fail, see application logs for more info !');
        }
    }

    private function findToken(bool $afterType = true): TokenInterface
    {
        foreach ($this->securityTokenRepository->findAll() as $token) {
            /** @var string|null $afterUrl */
            $afterUrl = $token->getAfterUrl();
            if ($afterType && null === $afterUrl) {
                return $token;
            }

            if (!$afterType && null !== $afterUrl) {
                return $token;
            }
        }

        throw new RuntimeException('Cannot find token, check if you are after proper checkout steps');
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return 'https://www.monetico-paiement.fr';
    }

    private function retrieveReference(TokenInterface $captureToken): string
    {
        $identity = $captureToken->getDetails();

        /** @var PaymentInterface $payment */
        $payment = $this->paymentRepository->find($identity->getId());
        $details = $payment->getDetails();

        return $details['reference'];
    }
}
