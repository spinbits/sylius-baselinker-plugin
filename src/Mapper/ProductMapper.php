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
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductMapper
{
    private CacheManager $cacheManager;
    private TaxRateResolverInterface $taxRateResolver;
    private RouterInterface $router;

    /**
     * @param CacheManager $cacheManager
     * @param TaxRateResolverInterface $taxRateResolver
     * @param RouterInterface $router
     */
    public function __construct(
        CacheManager $cacheManager,
        TaxRateResolverInterface $taxRateResolver,
        RouterInterface $router
    ) {
        $this->cacheManager = $cacheManager;
        $this->taxRateResolver = $taxRateResolver;
        $this->router = $router;
    }

    public function map(Product $product, ChannelInterface $channel): \Generator
    {
        /** @var LocaleInterface $defaultLocale */
        $defaultLocale = $channel->getDefaultLocale();
        yield [
            'sku' => $product->getCode(),
            'name' => $product->getName(),
            'tax' => $this->getTax($product, $channel),
            'description' => $product->getDescription(),
            'categoryId' => $this->getTaxon($product),
            'images' => $this->getImages($product),
            'variants' => $this->getVariants($product, $channel),
            'features' => $this->getFeatures($product),
            'allCategories' => $this->getTaxonomies($product),
            'allCategoriesExpanded' => $this->getTaxonomiesExpanded($product),
            'shortDescription' => $product->getShortDescription(),
            'slug' => $product->getSlug(),
            'url' => $this->router->generate('sylius_shop_product_show', [
                '_locale' => $defaultLocale->getCode(),
                'slug' => $product->getSlug(),
            ]),
        ];
    }

    private function getTax(Product $product, ChannelInterface $channel): int
    {
        /** @var ProductVariant $variant */
        $variant = $product->getVariants()->first();
        $criteria = ['zone' => $channel->getDefaultTaxZone()];
        $taxRate = $this->taxRateResolver->resolve($variant, $criteria);

        if (null === $taxRate) {
            return 0;
        }
        return (int) $taxRate->getAmount();
    }

    private function getTaxon(Product $product): string
    {
        /** @var TaxonInterface|null $mainTaxon */
        $mainTaxon = $product->getMainTaxon();
        if ($mainTaxon !== null) {
            return (string) $mainTaxon->getCode();
        }

        return '';
    }

    private function getImages(Product $product): array
    {
        $cache = $this->cacheManager;
        return $product->getImages()->map(function (ImageInterface $image) use ($cache): string {
            return $cache->getBrowserPath((string) parse_url((string) $image->getPath(), PHP_URL_PATH), 'sylius_admin_product_original');
        })->toArray();
    }

    private function getTaxonomies(Product $product): array
    {
        return $product->getTaxons()->map(function (TaxonInterface $taxon): string {
            return (string) $taxon->getName();
        })->toArray();
    }

    private function getTaxonomiesExpanded(Product $product): array
    {
        return $product->getTaxons()->map(function (TaxonInterface $taxon): string {
            return (string) $taxon->getFullname();
        })->toArray();
    }

    private function getVariants(Product $product, ChannelInterface $channel): array
    {
        $return = [];
        /** @var ProductVariantInterface $variant */
        foreach ($product->getVariants() as $variant) {
            $quantity = (int) $variant->getOnHand() - (int) $variant->getOnHold();
            $return[(int) $variant->getId()] = [
                'full_name' => $variant->getName(),
                'name' => $variant->getName(),
                'price' => $this->getPrice($variant, $channel),
                'quantity' => $quantity,
                'sku' => $variant->getCode(),
            ];
        }
        return $return;
    }

    private function getFeatures(Product $product): array
    {
        $return = [];
        foreach ($product->getAttributes() as $attribute) {
            $name = '';
            /** @var AttributeInterface|null $attr */
            $attr = $attribute->getAttribute();
            if ($attr !== null) {
                /** @var AttributeInterface $attr */
                $name = $attr->getName();
            }
            $return[(string) $attribute->getCode()] = [$name, $attribute->getValue()];
        }
        return array_values($return);
    }

    private function getPrice(ProductVariantInterface $productVariant, ChannelInterface $channel): float
    {
        $pricing = $productVariant->getChannelPricingForChannel($channel);

        if (null === $pricing) {
            return 0;
        }
        $price = (int) $pricing->getPrice();
        return  $price / 100;
    }
}
