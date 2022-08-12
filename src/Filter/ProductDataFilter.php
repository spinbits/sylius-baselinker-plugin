<?php

/**
 * @author Marcin Hubert <hubert.m.j@gmail.com>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Filter;

class ProductDataFilter extends AbstractFilter implements PaginatorFilterInterface
{
    public function getPage(): int
    {
        $page = (int) $this->get('page', 1);
        return $page < 1 ? 1 : $page;
    }

    public function getIds(): array
    {
        $list = (string) $this->get('products_id');
        return explode(",", $list);
    }

    public function getLimit(): int
    {
        return 50;
    }
}
