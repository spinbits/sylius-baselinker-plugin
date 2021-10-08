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
use Spinbits\SyliusBaselinkerPlugin\Mapper\ProductMapper;
use Spinbits\BaselinkerSdk\Filter\ProductDetailsFilter;
use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\BaselinkerSdk\Rest\Input;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

class ProductsDetailsActionHandler implements HandlerInterface
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
        $filter = new ProductDetailsFilter($input);
        $filter->setCustomFilter('channel_code', $this->channelContext->getChannel()->getCode());

        $channelCode = (string) $this->channelContext->getChannel()->getCode();
        $paginator = $this->productRepository->fetchBaseLinkerDetailedData($filter);

        $return = [];
        foreach ($paginator as $product) {
            $channel = $this->getProductChannel($product, $channelCode);
            if ($channel === null) {
                continue;
            }
            foreach ($this->mapper->map($product, $channel) as $variant) {
                $return[$product->getId()] = $variant;
            }
        }
        $return['pages'] = $paginator->getNbPages();
        return $return;
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
