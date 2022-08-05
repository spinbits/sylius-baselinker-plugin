<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Model;

use Spinbits\SyliusBaselinkerPlugin\Model\OrderAddModel;
use Spinbits\SyliusBaselinkerPlugin\Model\OrderUpdateModel;
use PHPUnit\Framework\TestCase;
use Spinbits\SyliusBaselinkerPlugin\Model\ProductModel;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

/** Class OrderAddModelTest */
class OrderAddModelTest extends TestCase
{
    /** @var OrderUpdateModel */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $data = [
            'email' => 'test@example.com',
            'products' => '[{"id":1,"name":"testowy przedmiot 1","price":100,"quantity":2},{"id":2,"name":"testowy przedmiot 2","price":150,"quantity":1,"attributes":[{"name":"kolor","value":"niebieski","price":0},{"name":"rozmiar","value":"XXL","price":20}]}]',
        ];
        $input = new Input($data);
        $this->sut = new OrderAddModel($input);
    }

    /** @test */
    public function testGetEmail()
    {
        $result = $this->sut->getEmail();
        $this->assertSame('test@example.com', $result);
    }

    /** @test */
    public function testGetProducts()
    {
        $result = $this->sut->getProducts();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(ProductModel::class, $result[0]);
        $this->assertInstanceOf(ProductModel::class, $result[1]);
        $this->assertSame('testowy przedmiot 1', $result[0]->getName());
        $this->assertSame('testowy przedmiot 2', $result[1]->getName());
    }
}
