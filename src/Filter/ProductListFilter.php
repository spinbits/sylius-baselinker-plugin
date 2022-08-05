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

use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

class ProductListFilter extends AbstractFilter implements PaginatorFilterInterface
{
    public function getLimit(): int
    {
        $limit = (int) $this->get('filter_limit', 100);

        if ($limit > 200) {
            return 200;
        }

        if ($limit < 1) {
            return 100;
        }

        return $limit;
    }

    public function getPage(): int
    {
        $page = (int) $this->get('page', 1);
        return $page < 1 ? 1 : $page;
    }

    public function hasId(): bool
    {
        return '' !== $this->getId();
    }

    public function getId(): ?string
    {
        return (string) $this->get('filter_id');
    }

    public function hasIds(): bool
    {
        return count($this->getIds()) > 0;
    }

    public function getIds(): array
    {
        $list = (string) $this->get('filter_ids_list');

        return strlen($list) > 0 ? explode(",", $list) : [];
    }

    public function hasCategory(): bool
    {
        return '' !== $this->getCategory();
    }

    public function getCategory(): string
    {
        $category = (string) $this->get('category_id');

        return $category === 'all' ? '' : $category;
    }

    public function hasPriceFrom(): bool
    {
        return null !== $this->getPriceFrom();
    }

    public function getPriceFrom(): ?float
    {
        return $this->getNullOrFloat('filter_price_from');
    }

    public function hasPriceTo(): bool
    {
        return null !== $this->getPriceTo();
    }

    public function getPriceTo(): ?float
    {
        return $this->getNullOrFloat('filter_price_to');
    }

    public function hasQuantityFrom(): bool
    {
        return null !== $this->getQuantityFrom();
    }

    public function getQuantityFrom(): ?float
    {
        return $this->getNullOrFloat('filter_quantity_from');
    }

    public function hasQuantityTo(): bool
    {
        return null !== $this->getQuantityTo();
    }

    public function getQuantityTo(): ?float
    {
        return $this->getNullOrFloat('filter_quantity_to');
    }

    public function hasSort(): bool
    {
        return count($this->getSort()) > 0;
    }

    public function getSort(): array
    {
        $filter = (string) $this->get('filter_sort');
        return strlen($filter) > 0 ? explode(" ", trim($filter)) : [];
    }

    private function getNullOrFloat(string $parameter): ?float
    {
        $filter = $this->get($parameter);
        return (null === $filter || '' === $filter ) ? null : (float) $filter;
    }
}
