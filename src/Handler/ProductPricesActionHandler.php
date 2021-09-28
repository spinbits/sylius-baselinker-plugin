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

use Spinbits\SyliusBaselinkerPlugin\Repository\BaseLinkerProductRepositoryInterface;
use Spinbits\BaselinkerSdk\Filter\PageOnlyFilter;
use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\BaselinkerSdk\Rest\Input;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductPricesActionHandler implements HandlerInterface
{
    private BaseLinkerProductRepositoryInterface $productRepository;
    private ChannelInterface $channel;

    public function __construct(
        BaseLinkerProductRepositoryInterface $productRepository,
        ChannelInterface $channel
    ) {
        $this->productRepository = $productRepository;
        $this->channel = $channel;
    }

    public function handle(Input $input): array
    {
        $filter = new PageOnlyFilter($input);
        $filter->setCustomFilter('channel_code', $this->channel->getCode());

        $paginator = $this->productRepository->fetchBaseLinkerPriceData($filter);
        $return = [];
        /* @var $product ProductInterface */
        foreach ($paginator as $product) {
            $variants = [];
            foreach ($product->getEnabledVariants() as $variant) {
                $variants[$variant->getId()] = $this->getPrice($variant);
            }
            $return[$product->getId()] = $variants;
        }
        $return['pages'] = $paginator->getNbPages();
        return $return;
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
