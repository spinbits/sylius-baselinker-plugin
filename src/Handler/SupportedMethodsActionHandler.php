<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Handler;

use Spinbits\SyliusBaselinkerPlugin\RequestHandler;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

class SupportedMethodsActionHandler implements HandlerInterface
{
    private RequestHandler $requestHandler;

    /**
     * @param RequestHandler $requestHandler
     */
    public function __construct(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Input $input): array
    {
        return $this->requestHandler->supportedActions();
    }
}
