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

class ProductModel extends AbstractDto
{
    protected ?string $id = null;
    protected ?string $variant_id;
    protected ?string $sku = null;
    protected ?string $name = null;
    protected ?int $price = null;
    /** @Assert\NotBlank */
    protected int $quantity;
    protected ?string $auction_id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getVariantId(): ?string
    {
        return $this->variant_id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAuctionId(): ?string
    {
        return $this->auction_id;
    }
}
