<?php

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Entity\Order;

trait OrderTrait
{

    private ?string $baselinkerOrderId = null;

    /**
     * @return string|null
     */
    public function getBaselinkerOrderId(): ?string
    {
        return $this->baselinkerOrderId;
    }

    /**
     * @param string|null $baselinkerOrderId
     */
    public function setBaselinkerOrderId(?string $baselinkerOrderId): void
    {
        $this->baselinkerOrderId = $baselinkerOrderId;
    }
}
