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

use Spinbits\BaselinkerSdk\Filter\AbstractFilter;
use Spinbits\BaselinkerSdk\Filter\PageOnlyFilter;
use Spinbits\BaselinkerSdk\Filter\PaginatorFilterInterface;
use Spinbits\BaselinkerSdk\Filter\ProductDetailsFilter;
use Spinbits\BaselinkerSdk\Filter\ProductListFilter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\LessThan1CurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;

trait ProductsRepositoryTrait
{
    private bool $pricingsJoined = false;
    private bool $translationsJoined = false;

    public function fetchBaseLinkerData(ProductListFilter $filter): PagerfantaInterface
    {
        $queryBuilder = $this->prepareBaseLinkerQueryBuilder($filter);
        $queryBuilder->andWhere('o.enabled = true');
        $this->applyFilters($queryBuilder, $filter);

        return $this->getPaginator($filter, $queryBuilder);
    }

    public function fetchBaseLinkerDetailedData(ProductDetailsFilter $filter): PagerfantaInterface
    {
        $queryBuilder = $this->prepareBaseLinkerQueryBuilder($filter);
        $this->filterByIds($queryBuilder, $filter->getIds());

        return $this->getPaginator($filter, $queryBuilder);
    }

    public function fetchBaseLinkerPriceData(PageOnlyFilter $filter): PagerfantaInterface {
        $queryBuilder = $this->prepareBaseLinkerQueryBuilder($filter);
        $queryBuilder->andWhere('o.enabled = true');

        return $this->getPaginator($filter, $queryBuilder);
    }

    public function fetchBaseLinkerQuantityData(PageOnlyFilter $filter): PagerfantaInterface
    {
        return $this->fetchBaseLinkerPriceData($filter);
    }

    private function prepareBaseLinkerQueryBuilder(AbstractFilter $filter): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->distinct();

        $queryBuilder
            ->leftJoin('o.channels', 'channel')
            ->andWhere('channel.code = :defaultChannelCode')
            ->setParameter('defaultChannelCode', $filter->getCustomFilter('channel_code'));
    }

    private function getPaginator(PaginatorFilterInterface $filter, QueryBuilder $queryBuilder): PagerfantaInterface
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
            $this->filterById($queryBuilder, $filter->getId());
        }

        if ($filter->hasPriceFrom()) {
            $this->filterPriceFrom($queryBuilder, $filter->getPriceFrom());
        }

        if ($filter->hasPriceTo()) {
            $this->filterPriceTo($queryBuilder, $filter->getPriceTo());
        }
        if ($filter->hasQuantityFrom()) {
            $this->filterQuantityFrom($queryBuilder, $filter->getQuantityFrom());
        }

        if ($filter->hasQuantityTo()) {
            $this->filterQuantityTo($queryBuilder, $filter->getQuantityTo());
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

    private function filterByCategory(QueryBuilder $queryBuilder, string $categoryCode)
    {
        $queryBuilder
            ->join('o.productTaxons', 'productTaxon')
            ->join('productTaxon.taxon', 'taxon')
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
            ->join('productVariant.channelPricings', 'pricing')
            ->andwhere('pricing.channelCode = :defaultChannelCode');
    }

    private function filterQuantityTo(QueryBuilder $queryBuilder, float $getQuantityTo)
    {
        $this->joinPricings($queryBuilder);
        $queryBuilder->andWhere('productVariant.onHand <= :quantityTo');
        $queryBuilder->setParameter('quantityTo', $getQuantityTo);
    }

    private function filterQuantityFrom(QueryBuilder $queryBuilder, float $getQuantityFrom)
    {
        $this->joinPricings($queryBuilder);
        $queryBuilder->andWhere('productVariant.onHand >= :quantityFrom');
        $queryBuilder->setParameter('quantityFrom', $getQuantityFrom);
    }

    private function sort(QueryBuilder $queryBuilder, array $sort)
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
        $queryBuilder->addOrderBy($field, $sort[1]??'ASC');
    }

    private function joinTranslations(QueryBuilder $queryBuilder)
    {
        if ($this->translationsJoined) {
            return;
        }
        $this->translationsJoined = true;
        $queryBuilder
            ->join('o.translations', 'translation');
    }
}
