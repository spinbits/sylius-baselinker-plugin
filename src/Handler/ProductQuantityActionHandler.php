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
use Sylius\Component\Core\Model\ProductInterface;

class ProductQuantityActionHandler implements HandlerInterface
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

        $paginator = $this->productRepository->fetchBaseLinkerQuantityData($filter);
        $return = [];
        /* @var $product ProductInterface */
        foreach ($paginator as $product) {
            $variants = [];
            foreach ($product->getEnabledVariants() as $variant) {
                $variants[$variant->getId()] = $variant->getOnHand();
            }
            $return[$product->getId()] = $variants;
        }
        $return['pages'] = $paginator->getNbPages();

        return $return;
    }
}
