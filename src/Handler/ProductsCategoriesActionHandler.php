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

use Spinbits\SyliusBaselinkerPlugin\Handler\HandlerInterface;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class ProductsCategoriesActionHandler implements HandlerInterface
{
    private TaxonRepositoryInterface $taxonRepository;
    private array $skipTaxonCodes;

    public function __construct(
        TaxonRepositoryInterface $taxonRepository,
        array $skipTaxonCodes = []
    ) {
        $this->taxonRepository = $taxonRepository;
        $this->skipTaxonCodes = $skipTaxonCodes;
    }

    public function handle(Input $input): array
    {
        /** @var TaxonInterface[] $taxons */
        $taxons = $this->taxonRepository->findBy(['enabled' => true]);

        $return = [];
        foreach ($taxons as $taxon) {
            if ($this->canHandle($taxon)) {
                $return[(string) $taxon->getCode()] = $taxon->getFullname();
            }
        }

        return $return;
    }

    /**
     * @param TaxonInterface $taxon
     * @return bool
     */
    private function canHandle(TaxonInterface $taxon): bool
    {
        return !in_array($taxon->getCode(), $this->skipTaxonCodes, true);
    }
}
