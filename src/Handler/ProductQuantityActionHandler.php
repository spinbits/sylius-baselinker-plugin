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
use Spinbits\SyliusBaselinkerPlugin\Filter\PageOnlyFilter;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductQuantityActionHandler implements HandlerInterface
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
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $filter = new PageOnlyFilter($input, $channel);

        $paginator = $this->productRepository->fetchBaseLinkerQuantityData($filter);
        $return = [];
        /** @var Product[] $paginator */
        foreach ($paginator as $product) {
            $variants = [];
            /** @var ProductVariantInterface $variant */
            foreach ($product->getEnabledVariants() as $variant) {
                $variants[(int) $variant->getId()] = $variant->getOnHand();
            }
            $return[(int) $product->getId()] = $variants;
        }
        /** @var Pagerfanta $paginator */
        $return['pages'] = $paginator->getNbPages();

        return $return;
    }
}
