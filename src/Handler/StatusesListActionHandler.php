<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Handler;

use Sylius\Component\Core\OrderCheckoutStates;
use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\BaselinkerSdk\Rest\Input;

class StatusesListActionHandler implements HandlerInterface
{
    public function handle(Input $input): array
    {
        return [
            OrderCheckoutStates::STATE_CART => 'Koszyk - w trakcie zamawiania',
            OrderCheckoutStates::STATE_COMPLETED => 'Ukończone',
        ];
    }
}
