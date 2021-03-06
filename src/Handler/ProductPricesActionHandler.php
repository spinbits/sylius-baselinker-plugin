<?php
/**
 * @author Marcin Hubert <>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Handler;

use Pagerfanta\Pagerfanta;
use Spinbits\SyliusBaselinkerPlugin\Repository\BaseLinkerProductRepositoryInterface;
use Spinbits\BaselinkerSdk\Filter\PageOnlyFilter;
use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\BaselinkerSdk\Rest\Input;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductPricesActionHandler implements HandlerInterface
{
    private BaseLinkerProductRepositoryInterface $productRepository;
    private ChannelContextInterface $channelContext;

    public function __construct(
        BaseLinkerProductRepositoryInterface $productRepository,
        ChannelContextInterface $channelContext
    ) {
        $this->productRepository = $productRepository;
        $this->channelContext = $channelContext;
    }

    public function handle(Input $input): array
    {
        $filter = new PageOnlyFilter($input);
        $filter->setCustomFilter('channel_code', $this->channelContext->getChannel()->getCode());

        $channelCode = (string) $this->channelContext->getChannel()->getCode();
        $paginator = $this->productRepository->fetchBaseLinkerPriceData($filter);
        $return = [];

        /** @var Product[] $paginator */
        foreach ($paginator as $product) {
            $channel = $this->getProductChannel($product, $channelCode);
            if ($channel === null) {
                continue;
            }

            $variants = [];
            /** @var ProductVariantInterface $variant */
            foreach ($product->getEnabledVariants() as $variant) {
                $variants[(int) $variant->getId()] = $this->getPrice($variant, $channel);
            }
            $return[(int) $product->getId()] = $variants;
        }
        /** @var Pagerfanta $paginator */
        $return['pages'] = $paginator->getNbPages();
        return $return;
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

    /**
     * @param Product $product
     * @param string $channelCode
     *
     * @return ChannelInterface|null
     */
    private function getProductChannel(Product $product, string $channelCode): ?ChannelInterface
    {
        /** @var ChannelInterface $channel */
        foreach ($product->getChannels() as $channel) {
            if ($channel->getCode() === $channelCode) {
                return $channel;
            }
        }

        return null;
    }
}
