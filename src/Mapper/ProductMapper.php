<?php
/**
 * @author Marcin Hubert <>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Mapper;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\ProductImageInterface;

class ProductMapper
{
    private ChannelInterface $channel;
    private CacheManager $cacheManager;

    public function __construct(ChannelInterface $channel, CacheManager $cacheManager)
    {
        $this->channel = $channel;
        $this->cacheManager = $cacheManager;
    }

    public function map(ProductInterface $product): \Generator
    {
        yield [
            'sku' => $product->getCode(),
            'name' => $product->getName(),
            'tax' => $this->getTax($product),
            'description' => $product->getDescription(),
            'categoryId' => $this->getTaxon($product),
            'images' => $this->getImages($product),
            'variants' => $this->getVariants($product),
            'features' => $this->getFeatures($product),
            // non standard fields
            'shortDescription' => $product->getShortDescription(),
            'slug' => $product->getSlug(),
            'url' => sprintf('https://digigames.pl/pl_PL/products/%s', $product->getSlug()),
        ];
    }

    private function getTax(ProductInterface $product): int
    {
        return 23;
    }

    private function getTaxon(ProductInterface $product): string
    {
        return $product->getMainTaxon()->getCode();
    }

    private function getImages(ProductInterface $product): array
    {
        $cache = $this->cacheManager;
        return $product->getImages()->map(function (ProductImageInterface $image) use ($cache) {
            return $cache->getBrowserPath(parse_url($image->getPath(), PHP_URL_PATH), 'sylius_admin_product_original');
        })->toArray();
    }

    private function getVariants(ProductInterface $product): array
    {
        $return = [];
        foreach ($product->getVariants() as $variant) {
            $return[$variant->getId()] = [
                'full_name' => $variant->getName(),
                'name' => $variant->getName(),
                'price' => $this->getPrice($variant),
                'quantity' => $variant->getOnHand() - $variant->getOnHold(),
                'sku' => $variant->getCode(),
            ];
        }
        return $return;
    }

    private function getFeatures(ProductInterface $product)
    {
        return $product->getAttributes()->map(function (AttributeValueInterface $attribute) {
            return [$attribute->getAttribute()->getName(), $attribute->getValue()];
        })->toArray();
    }

    private function getPrice(ProductVariantInterface $productVariant): float
    {
        $pricing = $productVariant->getChannelPricingForChannel($this->channel);
        if (null === $pricing) {
            return 0;
        }
        return $pricing->getPrice() / 100;
    }
}
