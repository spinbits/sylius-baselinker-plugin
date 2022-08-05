<?php

/**
 * @author Marcin Hubert <hubert.m.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Model;

use Symfony\Component\Validator\Constraints as Assert;

class OrderUpdateModel extends AbstractDto
{
    protected string $orders_ids;

    /**
     * @Assert\NotBlank
     * @Assert\Choice({"paid","status","delivery_number"})
     */
    protected string $update_type;

    /**
     * @Assert\NotBlank
     */
    protected string $update_value;

    /**
     * @Assert\NotBlank
     * @Assert\Count(min="1")
     */
    public function getOrdersIds(): array
    {
        return explode(",", $this->orders_ids);
    }

    public function getUpdateType(): string
    {
        return $this->update_type;
    }

    public function getUpdateValue(): string
    {
        return $this->update_value;
    }
}
