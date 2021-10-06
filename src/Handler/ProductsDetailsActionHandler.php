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

        /** @var ProductInterface[] $paginator */
        $paginator = $this->productRepository->fetchBaseLinkerDetailedData($filter);

        $return = [];
        foreach ($paginator as $product) {
            foreach ($this->mapper->map($product) as $variant) {
                $return[$product->getId()] = $variant;
            }
        }
        $return['pages'] = $paginator->getNbPages();
        return $return;
    }
}
