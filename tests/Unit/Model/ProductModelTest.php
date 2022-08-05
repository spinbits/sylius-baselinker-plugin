<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Model;

use Spinbits\SyliusBaselinkerPlugin\Model\ProductModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

/** Class ProductModelTest */
class ProductModelTest extends TestCase
{
    /** @var ProductModel */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $input = [
            'id' => '193',
            'variant_id' => '132',
            'sku' => 'sku-code',
            'name' => 'some-name',
            'price' => '123.32',
            'quantity' => '3',
            'auction_id' => 'asd',
        ];
        $input = new Input($input);
        $this->sut = new ProductModel($input);
    }

    /** @test */
    public function testGetId()
    {
        $result = $this->sut->getId();
        $this->assertSame('193', $result);
    }

    /** @test */
    public function testGetVariantId()
    {
        $result = $this->sut->getVariantId();
        $this->assertSame('132', $result);
    }

    /** @test */
    public function testGetSku()
    {
        $result = $this->sut->getSku();
        $this->assertSame('sku-code', $result);
    }

    /** @test */
    public function testGetName()
    {
        $result = $this->sut->getName();
        $this->assertSame('some-name', $result);
    }

    /** @test */
    public function testGetPrice()
    {
        $result = $this->sut->getPrice();
        $this->assertSame(123, $result);
    }
    /** @test */
    public function testGetQuantity()
    {
        $result = $this->sut->getQuantity();
        $this->assertSame(3, $result);
    }

    /** @test */
    public function testGetAuctionId()
    {
        $result = $this->sut->getAuctionId();
        $this->assertSame('asd', $result);
    }
}
