<?php

declare(strict_types=1);

namespace Tests\FluxSE\SyliusPayumMoneticoPlugin\App\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Symfony\Action\GetHttpRequestAction as BaseGetHttpRequestAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Symfony\Component\HttpFoundation\RequestStack;

final class GetHttpRequestAction implements ActionInterface
{
    /**
     * @var BaseGetHttpRequestAction
     */
    private $decoratedGetHttpRequestAction;

    /**
     * @var RequestStack
     */
    protected $httpRequestStack;

    public function __construct(
        BaseGetHttpRequestAction $decoratedGetHttpRequestAction,
        RequestStack $httpRequestStack
    ) {
        $this->decoratedGetHttpRequestAction = $decoratedGetHttpRequestAction;
        $this->httpRequestStack = $httpRequestStack;
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        if ($this->httpRequestStack->getCurrentRequest() !== $this->httpRequestStack->getMainRequest())
        {
            $this->decoratedGetHttpRequestAction->setHttpRequest($this->httpRequestStack->getCurrentRequest());
        }
        $this->decoratedGetHttpRequestAction->execute($request);
    }

    public function supports($request): bool
    {
        return $this->decoratedGetHttpRequestAction->supports($request);
    }
}
