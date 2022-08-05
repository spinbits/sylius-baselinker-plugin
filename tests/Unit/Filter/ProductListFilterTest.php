<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Filter;

use Spinbits\SyliusBaselinkerPlugin\Filter\ProductListFilter;
use PHPUnit\Framework\TestCase;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

/** Class ProductListFilterTest */
class ProductListFilterTest extends TestCase
{
    /** @test */
    public function testGetLimitReturnMinimum1()
    {
        $sut = $this->provideTestObject('filter_limit', '-5');
        $result = $sut->getLimit();

        $this->assertEquals(100, $result);
    }

    /** @test */
    public function testGetLimitReturnMaximum200()
    {
        $sut = $this->provideTestObject('filter_limit', '+201');
        $result = $sut->getLimit();

        $this->assertEquals(200, $result);
    }

    /** @test */
    public function testGetLimit()
    {
        $sut = $this->provideTestObject('filter_limit', '50');
        $result = $sut->getLimit();

        $this->assertEquals(50, $result);
    }

    /** @test */
    public function testGetPage()
    {
        $sut = $this->provideTestObject('page', '5');

        $result = $sut->getPage();
        $this->assertEquals(5, $result);
    }

    /** @test */
    public function testGetPageReturnsMinimumOne()
    {
        $sut = $this->provideTestObject('page', '-15');

        $result = $sut->getPage();
        $this->assertEquals(1, $result);
    }

    /** @test */
    public function testHasIdReturnFalseIfEmptyString()
    {
        $sut = $this->provideTestObject('filter_id', '');

        $result = $sut->hasId();
        $this->assertFalse($result);
    }

    /** @test */
    public function testHasId()
    {
        $sut = $this->provideTestObject('filter_id', '5');

        $result = $sut->hasId();
        $this->assertTrue($result);
    }

    /** @test */
    public function testGetId()
    {
        $sut = $this->provideTestObject('filter_id', '5');

        $result = $sut->getId();
        $this->assertSame("5", $result);
    }

    /** @test */
    public function testHasIdsReturnFalseOnEmptyString()
    {
        $sut = $this->provideTestObject('filter_ids_list', '');

        $result = $sut->hasIds();
        $this->assertFalse($result);
    }

    /** @test */
    public function testHasIds()
    {
        $sut = $this->provideTestObject('filter_ids_list', '5,4,3');

        $result = $sut->hasIds();
        $this->assertTrue($result);
    }

    /** @test */
    public function testGetIdsReturnEmptyArray()
    {
        $sut = $this->provideTestObject('filter_ids_list', '');

        $result = $sut->getIds();
        $this->assertSame([], $result);
    }

    /** @test */
    public function testGetIds()
    {
        $sut = $this->provideTestObject('filter_ids_list', '5,4,3');

        $result = $sut->getIds();
        $this->assertSame(["5", "4", "3"], $result);
    }

    /** @test */
    public function testHasCategoryReturnFalse()
    {
        $sut = $this->provideTestObject('category_id', '');

        $result = $sut->hasCategory();
        $this->assertFalse($result);
    }

    /** @test */
    public function testHasCategory()
    {
        $sut = $this->provideTestObject('category_id', '5');


        $result = $sut->hasCategory();
        $this->assertTrue($result);
    }

    /** @test */
    public function testGetCategory()
    {
        $sut = $this->provideTestObject('category_id', '5');

        $result = $sut->getCategory();
        $this->assertSame("5", $result);
    }

    /** @test */
    public function testGetCategoryReturnEmptyStringWhenAllValuePassed()
    {
        $sut = $this->provideTestObject('category_id', 'all');

        $result = $sut->getCategory();
        $this->assertSame("", $result);
    }



    /** @test */
    public function testHasPriceFrom()
    {
        $sut = $this->provideTestObject('filter_price_from', '1.1');

        $result = $sut->hasPriceFrom();
        $this->assertTrue($result);
    }

    /** @test */
    public function testHasPriceFromFalseWhenEmptyString()
    {
        $sut = $this->provideTestObject('filter_price_from', '');

        $result = $sut->hasPriceFrom();
        $this->assertFalse($result);
    }

    /** @test */
    public function testGetPriceFrom()
    {
        $sut = $this->provideTestObject('filter_price_from', '1.1');

        $result = $sut->getPriceFrom();
        $this->assertSame(1.1, $result);
    }

    /** @test */
    public function testHasPriceTo()
    {
        $sut = $this->provideTestObject('filter_price_to', '1.1');

        $result = $sut->hasPriceTo();
        $this->assertTrue($result);
    }

    /** @test */
    public function testHasPriceToFalseWhenEmptyString()
    {
        $sut = $this->provideTestObject('filter_price_to', '');

        $result = $sut->hasPriceTo();
        $this->assertFalse($result);
    }

    /** @test */
    public function testGetPriceTo()
    {
        $sut = $this->provideTestObject('filter_price_to', '1.1');

        $result = $sut->getPriceTo();
        $this->assertSame(1.1, $result);
    }

    /** @test */
    public function testHasQuantityFromFalseWhenEmptyString()
    {
        $sut = $this->provideTestObject('filter_quantity_from', '');

        $result = $sut->hasQuantityFrom();
        $this->assertFalse($result);
    }

    /** @test */
    public function testHasQuantityFrom()
    {
        $sut = $this->provideTestObject('filter_quantity_from', '1.1');

        $result = $sut->hasQuantityFrom();
        $this->assertTrue($result);
    }

    /** @test */
    public function testGetQuantityFrom()
    {
        $sut = $this->provideTestObject('filter_quantity_from', '1.1');

        $result = $sut->getQuantityFrom();
        $this->assertSame(1.1, $result);
    }

    /** @test */
    public function testHasQuantityToReturnFalseOnEmpty()
    {
        $sut = $this->provideTestObject('filter_quantity_to', '');

        $result = $sut->hasQuantityTo();
        $this->assertFalse($result);
    }
//
//    /** @test */
//    public function testHasQuantityTo()
//    {
//        $sut = $this->provideTestObject('filter_quantity_to', '1.1');
//
//        $result = $sut->hasQuantityTo();
//        $this->assertTrue($result);
//    }

    /** @test */
    public function testGetQuantityToReturnNullWhenNotSet()
    {
        $sut = $this->provideTestObject('filter_quantity_to', null);

        $result = $sut->getQuantityTo();
        $this->assertSame(null, $result);
    }

    /** @test */
    public function testGetQuantityTo()
    {
        $sut = $this->provideTestObject('filter_quantity_to', '1.1');

        $result = $sut->getQuantityTo();
        $this->assertSame(1.1, $result);
    }

    /** @test */
    public function testGetSort()
    {
        $sut = $this->provideTestObject('filter_sort', 'id ASC');

        $result = $sut->getSort();
        $this->assertSame([0 => 'id', 1 => 'ASC'], $result);
    }

    /** @test */
    public function testHasSort()
    {
        $sut = $this->provideTestObject('filter_sort', 'id ASC');

        $result = $sut->hasSort();
        $this->assertTrue($result);
    }

    private function provideTestObject(string $param, $value): ProductListFilter
    {
        $input = $this->createMock(Input::class);

        $input->expects($this->once())
            ->method('get')
            ->with(...[$param])
            ->willReturn($value);

        return new ProductListFilter($input);
    }
}
