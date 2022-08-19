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
use Spinbits\SyliusBaselinkerPlugin\Filter\PageOnlyFilter;
use Spinbits\SyliusBaselinkerPlugin\Filter\ProductDataFilter;
use Spinbits\SyliusBaselinkerPlugin\Filter\ProductListFilter;
use Sylius\Component\Core\Model\Product;

interface BaseLinkerProductRepositoryInterface
{
    public function fetchBaseLinkerData(ProductListFilter $filter): Pagerfanta;

    public function fetchBaseLinkerPriceData(PageOnlyFilter $filter): Pagerfanta;

    public function fetchBaseLinkerQuantityData(PageOnlyFilter $filter): Pagerfanta;

    public function fetchBaseLinkerDetailedData(ProductDataFilter $filter): Pagerfanta;
}
