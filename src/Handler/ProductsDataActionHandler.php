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
use Spinbits\SyliusBaselinkerPlugin\Mapper\ProductMapper;
use Spinbits\SyliusBaselinkerPlugin\Filter\ProductDataFilter;
use Spinbits\SyliusBaselinkerPlugin\Handler\HandlerInterface;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductsDataActionHandler implements HandlerInterface
{
    private ProductMapper $mapper;
    private BaseLinkerProductRepositoryInterface $productRepository;
    private ChannelContextInterface $channelContext;

    public function __construct(
        ProductMapper $mapper,
        BaseLinkerProductRepositoryInterface $productRepository,
        ChannelContextInterface $channelContext
    ) {
        $this->mapper = $mapper;
        $this->productRepository = $productRepository;
        $this->channelContext = $channelContext;
    }

    public function handle(Input $input): array
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $filter = new ProductDataFilter($input, $channel);

        $paginator = $this->productRepository->fetchBaseLinkerDetailedData($filter);

        $return = [];
        /** @var Product[] $paginator */
        foreach ($paginator as $product) {
            /** @var ProductVariantInterface $variant */
            foreach ($this->mapper->map($product, $channel) as $variant) {
                $return[(int) $product->getId()] = $variant;
            }
        }
        /** @var Pagerfanta $paginator */
        $return['pages'] = $paginator->getNbPages();
        return $return;
    }
}
