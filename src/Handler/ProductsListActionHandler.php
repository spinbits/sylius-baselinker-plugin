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
use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\SyliusBaselinkerPlugin\Repository\BaseLinkerProductRepositoryInterface;
use Spinbits\SyliusBaselinkerPlugin\Mapper\ListProductMapper;
use Spinbits\BaselinkerSdk\Filter\ProductListFilter;
use Spinbits\BaselinkerSdk\Rest\Input;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductsListActionHandler implements HandlerInterface
{
    private ListProductMapper $mapper;
    private BaseLinkerProductRepositoryInterface $productRepository;
    private ChannelContextInterface $channelContext;

    public function __construct(
        ListProductMapper $mapper,
        BaseLinkerProductRepositoryInterface $productRepository,
        ChannelContextInterface $channel
    ) {
        $this->mapper = $mapper;
        $this->productRepository = $productRepository;
        $this->channelContext = $channel;
    }

    public function handle(Input $input): array
    {
        $filter = new ProductListFilter($input);
        $filter->setCustomFilter('channel_code', $this->channelContext->getChannel()->getCode());

        $channelCode = (string) $this->channelContext->getChannel()->getCode();
        $paginator = $this->productRepository->fetchBaseLinkerData($filter);
        $return = [];
        /** @var ProductInterface[] $paginator */
        foreach ($paginator as $product) {
            $channel = $this->getProductChannel($product, $channelCode);
            if ($channel === null) {
                continue;
            }

            /** @var ProductVariantInterface $variant */
            foreach ($this->mapper->map($product, $channel) as $variant) {
                $return[(int) $product->getId()] = $variant;
            }
        }
        /** @var Pagerfanta $paginator */
        $return['pages'] = $paginator->getNbPages();
        return  $return;
    }

    /**
     * @param ProductInterface $product
     * @param string $channelCode
     *
     * @return ChannelInterface|null
     */
    private function getProductChannel(ProductInterface $product, string $channelCode): ?ChannelInterface
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
