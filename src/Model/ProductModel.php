<?php

/**
 * @author Marcin Hubert <hubert.m.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Model;

use Spinbits\SyliusBaselinkerPlugin\Rest\Input;
use Symfony\Component\Validator\Constraints as Assert;

class ProductModel
{
    /** @Assert\NotBlank */
    private string $id;
    private string $variant_id;
    private string $sku;
    private string $name;
    private int $price;
    /** @Assert\NotBlank */
    private int $quantity;
    private string $auction_id;

    public function __construct(Input $input)
    {
        $this->id = (string) $input->get('id');
        $this->variant_id = (string)  $input->get('variant_id');
        $this->sku = (string)  $input->get('sku');
        $this->name = (string)  $input->get('name');
        $this->price = (int) $input->get('price');
        $this->quantity = (int) $input->get('quantity');
        $this->auction_id = (string) $input->get('auction_id');
    }

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
