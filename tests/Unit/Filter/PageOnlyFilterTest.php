<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Filter;

use Spinbits\SyliusBaselinkerPlugin\Filter\PageOnlyFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

/** Class PageOnlyFilterTest */
class PageOnlyFilterTest extends TestCase
{
    /** @test */
    public function testGetLimitWillReturnAlways300()
    {
        $input = $this->createMock(Input::class);

        $input->expects($this->never())
            ->method('get');

        $this->sut = new PageOnlyFilter($input);

        $result = $this->sut->getLimit();
        $this->assertEquals(300, $result);
    }

    /** @test */
    public function testGetPage()
    {
        $input = $this->createMock(Input::class);

        $input->expects($this->once())
            ->method('get')
            ->with(...['page'])
            ->willReturn(5);

        $this->sut = new PageOnlyFilter($input);

        $result = $this->sut->getPage();
        $this->assertEquals(5, $result);
    }

    /** @test */
    public function testGetPageReturnsMinimumOne()
    {
        $input = $this->createMock(Input::class);

        $input->expects($this->once())
            ->method('get')
            ->with(...['page'])
            ->willReturn(-15);

        $this->sut = new PageOnlyFilter($input);

        $result = $this->sut->getPage();
        $this->assertEquals(1, $result);
    }
}
