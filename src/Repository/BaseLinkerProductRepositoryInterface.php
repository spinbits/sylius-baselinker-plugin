<?php
/**
 * @author Marcin Hubert <>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Repository;

use Pagerfanta\Pagerfanta;
use Spinbits\BaselinkerSdk\Filter\PageOnlyFilter;
use Spinbits\BaselinkerSdk\Filter\ProductDetailsFilter;
use Spinbits\BaselinkerSdk\Filter\ProductListFilter;
use Sylius\Component\Core\Model\Product;

interface BaseLinkerProductRepositoryInterface
{
    public function fetchBaseLinkerData(ProductListFilter $filter): Pagerfanta;

    public function fetchBaseLinkerPriceData(PageOnlyFilter $filter): Pagerfanta;

    public function fetchBaseLinkerQuantityData(PageOnlyFilter $filter): Pagerfanta;

    public function fetchBaseLinkerDetailedData(ProductDetailsFilter $filter): Pagerfanta;
}
