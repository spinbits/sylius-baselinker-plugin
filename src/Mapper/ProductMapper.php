<?php
/**
 * @author Marcin Hubert <>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Mapper;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\ProductImageInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductMapper
{
    private ChannelContextInterface $channel;
    private CacheManager $cacheManager;
    private TaxRateResolverInterface $taxRateResolver;
    private RouterInterface $router;

    /**
     * @param ChannelContextInterface $channel
     * @param CacheManager $cacheManager
     * @param TaxRateResolverInterface $taxRateResolver
     * @param RouterInterface $router
     */
    public function __construct(
        ChannelContextInterface $channel,
        CacheManager $cacheManager,
        TaxRateResolverInterface $taxRateResolver,
        RouterInterface $router
    ) {
        $this->channel = $channel;
        $this->cacheManager = $cacheManager;
        $this->taxRateResolver = $taxRateResolver;
        $this->router = $router;
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
            'allCategories' => $this->getTaxonomies($product),
            'allCategoriesExpanded' => $this->getTaxonomiesExpanded($product),
            'shortDescription' => $product->getShortDescription(),
            'slug' => $product->getSlug(),
            'url' => $this->router->generate('sylius_shop_product_show', [
                '_locale' => $this->channel->getChannel()->getDefaultLocale()->getCode(),
                'slug' => $product->getSlug(),
            ]),
        ];
    }

    private function getTax(ProductInterface $product): int
    {
        $criteria = ['zone' => $this->channel->getChannel()->getDefaultTaxZone()];
        $taxRate = $this->taxRateResolver->resolve($product->getVariants()->first(), $criteria);

        if (null === $taxRate) {
            return 0;
        }
        return (int) $taxRate->getAmount();
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

    private function getTaxonomies(ProductInterface $product): array
    {
        return $product->getTaxons()->map(function (TaxonInterface $taxon) {
            return $taxon->getName();
        })->toArray();
    }

    private function getTaxonomiesExpanded(ProductInterface $product): array
    {
        return $product->getTaxons()->map(function (TaxonInterface $taxon) {
            return $taxon->getFullname();
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
        $return = [];
        foreach ($product->getAttributes() as $attribute) {
            $return[$attribute->getCode()] = [$attribute->getAttribute()->getName(), $attribute->getValue()];
        }
        $return = array_values($return);
        return $return;
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
