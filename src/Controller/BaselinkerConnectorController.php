<?php

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Controller;

use Spinbits\BaselinkerSdk\RequestHandler;
use Spinbits\BaselinkerSdk\Rest\Input;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BaselinkerConnectorController extends AbstractController
{
    private RequestHandler $requestHandler;

    public function connectorAction(Request $request): Response
    {
        $input = new Input($request->request->all());
        $response = $this->requestHandler->handle($input);

        return new JsonResponse($response->content(), $response->code());
    }
}
