<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Handler;

use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\BaselinkerSdk\RequestHandler;
use Spinbits\BaselinkerSdk\Rest\Input;

class SupportedMethodsAction implements HandlerInterface
{
    /** @var RequestHandler */
    private $requestHandler;

    /**
     * @param RequestHandler $requestHandler
     */
    public function __construct(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    public function handle(Input $input): array
    {
        return $this->requestHandler->supportedActions();
    }
}
