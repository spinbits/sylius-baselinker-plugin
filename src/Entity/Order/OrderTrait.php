<?php

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

trait OrderTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="baselinker_order_id", type="string", nullable=true, length=32)
     */
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
