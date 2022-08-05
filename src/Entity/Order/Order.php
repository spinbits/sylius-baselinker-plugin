<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Entity\Order;

use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder
{
    use OrderTrait;
}
