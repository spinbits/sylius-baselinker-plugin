<?php
/**
 * @author Marcin Hubert <>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Mapper;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

class ListProductMapper
{
    private ChannelContextInterface $channel;

    public function __construct(ChannelContextInterface $channel)
    {
        $this->channel = $channel;
    }

    public function map(ProductInterface $product): \Generator
    {
        foreach ($product->getVariants() as $variant) {
            yield [
                'name' => $product->getName(),
                'quantity' => $variant->getOnHand(),
                'price' => $this->getPrice($variant),
                'ean' => null, // not required
                'sku' => $product->getCode(), // not required
            ];
        }
    }

    private function getPrice(ProductVariantInterface $productVariant): float
    {
        $pricing = $productVariant->getChannelPricingForChannel($this->channel->getChannel());
        if (null === $pricing) {
            return 0;
        }
        return $pricing->getPrice() / 100;
    }
}
