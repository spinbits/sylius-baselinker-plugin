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
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariant;
use \Sylius\Component\Core\Model\Product;

class ListProductMapper
{
    private ChannelContextInterface $channelContext;

    public function __construct(ChannelContextInterface $channel)
    {
        $this->channelContext = $channel;
    }

    public function map(Product $product, ChannelInterface $channel): \Generator
    {
        /** @var ProductVariant $variant */
        foreach ($product->getVariants() as $variant) {
            yield [
                'name' => $product->getName(),
                'quantity' => $variant->getOnHand(),
                'price' => $this->getPrice($variant, $channel),
                'ean' => null, // not required
                'sku' => $product->getCode(), // not required
            ];
        }
    }

    private function getPrice(\Sylius\Component\Core\Model\ProductVariantInterface $productVariant, ChannelInterface $channel): float
    {
        $pricing = $productVariant->getChannelPricingForChannel($channel);

        if (null === $pricing) {
            return 0;
        }
        $price = (int) $pricing->getPrice();
        return  $price / 100;
    }
}
