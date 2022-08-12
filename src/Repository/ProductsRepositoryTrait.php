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

use Spinbits\SyliusBaselinkerPlugin\Filter\AbstractFilter;
use Spinbits\SyliusBaselinkerPlugin\Filter\PageOnlyFilter;
use Spinbits\SyliusBaselinkerPlugin\Filter\PaginatorFilterInterface;
use Spinbits\SyliusBaselinkerPlugin\Filter\ProductDataFilter;
use Spinbits\SyliusBaselinkerPlugin\Filter\ProductListFilter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\LessThan1CurrentPageException;
use Pagerfanta\Pagerfanta;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;

trait ProductsRepositoryTrait
{
    private bool $pricingsJoined = false;
    private bool $translationsJoined = false;

    public function fetchBaseLinkerData(ProductListFilter $filter): Pagerfanta
    {
        $queryBuilder = $this->prepareBaseLinkerQueryBuilder($filter);
        $queryBuilder->andWhere('o.enabled = true');
        $this->applyFilters($queryBuilder, $filter);

        return $this->appendPaginator($filter, $queryBuilder);
    }

    public function fetchBaseLinkerDetailedData(ProductDataFilter $filter): Pagerfanta
    {
        $queryBuilder = $this->prepareBaseLinkerQueryBuilder($filter);
        $this->filterByIds($queryBuilder, $filter->getIds());

        return $this->appendPaginator($filter, $queryBuilder);
    }

    public function fetchBaseLinkerPriceData(PageOnlyFilter $filter): Pagerfanta
    {
        $queryBuilder = $this->prepareBaseLinkerQueryBuilder($filter);
        $queryBuilder->andWhere('o.enabled = true');

        return $this->appendPaginator($filter, $queryBuilder);
    }

    public function fetchBaseLinkerQuantityData(PageOnlyFilter $filter): Pagerfanta
    {
        return $this->fetchBaseLinkerPriceData($filter);
    }

    private function prepareBaseLinkerQueryBuilder(AbstractFilter $filter): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->distinct()
            ->andWhere(':channel MEMBER OF o.channels')
            ->setParameter('channel', $filter->getChannel());

        return $queryBuilder;
    }

    private function appendPaginator(PaginatorFilterInterface $filter, QueryBuilder $queryBuilder): Pagerfanta
    {
        $paginator = new Pagerfanta(new QueryAdapter($queryBuilder));
        $paginator->setNormalizeOutOfRangePages(true);
        $paginator->setMaxPerPage($filter->getLimit());
        try {
            $paginator->setCurrentPage($filter->getPage());
        } catch (LessThan1CurrentPageException $exception) {
            // ignore
        }

        return $paginator;
    }

    private function applyFilters(QueryBuilder $queryBuilder, ProductListFilter $filter): void
    {
        if ($filter->hasId()) {
            $this->filterById($queryBuilder, (string) $filter->getId());
        }

        if ($filter->hasPriceFrom()) {
            $this->filterPriceFrom($queryBuilder, (float) $filter->getPriceFrom());
        }

        if ($filter->hasPriceTo()) {
            $this->filterPriceTo($queryBuilder, (float) $filter->getPriceTo());
        }
        if ($filter->hasQuantityFrom()) {
            $this->filterQuantityFrom($queryBuilder, (float) $filter->getQuantityFrom());
        }

        if ($filter->hasQuantityTo()) {
            $this->filterQuantityTo($queryBuilder, (float) $filter->getQuantityTo());
        }

        if ($filter->hasIds()) {
            $this->filterByIds($queryBuilder, $filter->getIds());
        }

        if ($filter->hasCategory()) {
            $this->filterByCategory($queryBuilder, $filter->getCategory());
        }

        if ($filter->hasSort()) {
            $this->sort($queryBuilder, $filter->getSort());
        }
    }

    private function filterById(QueryBuilder $queryBuilder, string $id): void
    {
        $queryBuilder->andWhere('o.id = :id');
        $queryBuilder->setParameter('id', $id);
    }

    private function filterPriceFrom(QueryBuilder $queryBuilder, float $priceFrom): void
    {
        $this->joinPricings($queryBuilder);
        $queryBuilder
            ->andWhere('pricing.price >= :priceFrom')
            ->setParameter('priceFrom', (int)($priceFrom * 100));
    }

    private function filterPriceTo(QueryBuilder $queryBuilder, float $priceTo): void
    {
        $this->joinPricings($queryBuilder);
        $queryBuilder
            ->andWhere('pricing.price <= :priceTo')
            ->setParameter('priceTo', (int)($priceTo * 100));
    }

    private function filterByIds(QueryBuilder $queryBuilder, array $ids): void
    {
        $queryBuilder->andWhere('o.id IN (:ids)');
        $queryBuilder->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY);
    }

    private function filterByCategory(QueryBuilder $queryBuilder, string $categoryCode): void
    {
        $queryBuilder
            ->innerJoin('o.productTaxons', 'productTaxon')
            ->innerJoin('productTaxon.taxon', 'taxon')
            ->andWhere('taxon.code = :taxonCode')
            ->setParameter('taxonCode', $categoryCode);
    }

    private function joinPricings(QueryBuilder $queryBuilder): void
    {
        if ($this->pricingsJoined) {
            return;
        }
        $this->pricingsJoined = true;
        $queryBuilder
            ->join('o.variants', 'productVariant')
            ->join('productVariant.channelPricings', 'pricing');
//            ->andWhere('pricing.channelCode = :channel');
    }

    private function filterQuantityTo(QueryBuilder $queryBuilder, float $getQuantityTo): void
    {
        $this->joinPricings($queryBuilder);
        $queryBuilder->andWhere('productVariant.onHand <= :quantityTo');
        $queryBuilder->setParameter('quantityTo', $getQuantityTo);
    }

    private function filterQuantityFrom(QueryBuilder $queryBuilder, float $getQuantityFrom): void
    {
        $this->joinPricings($queryBuilder);
        $queryBuilder->andWhere('productVariant.onHand >= :quantityFrom');
        $queryBuilder->setParameter('quantityFrom', $getQuantityFrom);
    }

    private function sort(QueryBuilder $queryBuilder, array $sort): void
    {
        switch ($sort[0] ?? null) {
            case 'name':
                $this->joinTranslations($queryBuilder);
                $field = 'translation.name';
                break;
            case 'price':
                $this->joinPricings($queryBuilder);
                $field = 'pricing.price';
                break;
            case 'quantity':
                $this->joinPricings($queryBuilder);
                $field = 'productVariant.onHand';
                break;
            case 'id':
            default:
                $field = 'o.id';
                break;
        }
        $order = $sort[1] !== null ? (string) $sort[1] : 'ASC';
        $queryBuilder->addOrderBy($field, $order);
    }

    private function joinTranslations(QueryBuilder $queryBuilder): void
    {
        if ($this->translationsJoined) {
            return;
        }
        $this->translationsJoined = true;
        $queryBuilder
            ->join('o.translations', 'translation');
    }
}
