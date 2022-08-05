<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Model;

use Spinbits\SyliusBaselinkerPlugin\Model\OrderUpdateModel;
use PHPUnit\Framework\TestCase;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

/** Class OrderUpdateModelTest */
class OrderUpdateModelTest extends TestCase
{
    /** @var OrderUpdateModel */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $input = [
            'orders_ids' => '193,123',
            'update_type' => 'paid',
            'update_value' => 'asd',
        ];
        $input = new Input($input);
        $this->sut = new OrderUpdateModel($input);
    }

    /** @test */
    public function testGetOrderIds()
    {
        $result = $this->sut->getOrdersIds();
        $this->assertSame(['193', '123'], $result);
    }

    /** @test */
    public function testGetUpdateType()
    {
        $result = $this->sut->getUpdateType();
        $this->assertSame('paid', $result);
    }

    /** @test */
    public function testGetUpdateValue()
    {
        $result = $this->sut->getUpdateValue();
        $this->assertSame('asd', $result);
    }
}
