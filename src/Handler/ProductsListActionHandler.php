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

use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\SyliusBaselinkerPlugin\Repository\BaseLinkerProductRepositoryInterface;
use Spinbits\SyliusBaselinkerPlugin\Mapper\ListProductMapper;
use Spinbits\BaselinkerSdk\Filter\ProductListFilter;
use Spinbits\BaselinkerSdk\Rest\Input;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ProductInterface;

class ProductsListActionHandler implements HandlerInterface
{
    private ListProductMapper $mapper;
    private BaseLinkerProductRepositoryInterface $productRepository;
    private ChannelContextInterface $channel;

    public function __construct(
        ListProductMapper $mapper,
        BaseLinkerProductRepositoryInterface $productRepository,
        ChannelContextInterface $channel
    ) {
        $this->mapper = $mapper;
        $this->productRepository = $productRepository;
        $this->channel = $channel;
    }

    public function handle(Input $input): array
    {
        $filter = new ProductListFilter($input);
        $filter->setCustomFilter('channel_code', $this->channel->getChannel()->getCode());

        /** @var ProductInterface[] $paginator */
        $paginator = $this->productRepository->fetchBaseLinkerData($filter);
        $return = [];
        foreach ($paginator as $product) {
            foreach ($this->mapper->map($product) as $variant) {
                $return[$product->getId()] = $variant;
            }
        }
        $return['pages'] = $paginator->getNbPages();
        return  $return;
    }
}
