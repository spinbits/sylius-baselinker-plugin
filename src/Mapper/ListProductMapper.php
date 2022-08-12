<?php

/**
 * @author Marcin Hubert <>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Mapper;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ListProductMapper
{
    public function map(Product $product, ChannelInterface $channel): \Generator
    {
        /** @var ProductVariantInterface $variant */
        foreach ($product->getVariants() as $variant) {
            yield [
                'name' => $product->getName() . ' ' . $variant->getName(),
                'quantity' => $variant->getOnHand(),
                'price' => $this->getPrice($variant, $channel),
                'ean' => null, // not required
                'sku' => $variant->getCode(), // not required
                'location' => 'default',
                'currency' => $channel->getBaseCurrency()?->getCode(),
            ];
        }
    }

    private function getPrice(ProductVariantInterface $variant, ChannelInterface $channel): float
    {
        return round(intval($variant->getChannelPricingForChannel($channel)?->getPrice()) / 100, 2);
    }
}
