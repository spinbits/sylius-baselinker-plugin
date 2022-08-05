<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Filter;

use Spinbits\SyliusBaselinkerPlugin\Filter\AbstractFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

/** Class AbstractFilterTest */
class AbstractFilterTest extends TestCase
{
    /** @var AbstractFilter */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $input = $this->createMock(Input::class);
        $this->sut = new AbstractFilter($input);
    }

    /** @test */
    public function testSetCustomFilter()
    {
        $result = $this->sut->setCustomFilter('filter', 'value');

        $this->assertNull($result);
    }

    /** @test */
    public function testGetCustomFilter()
    {
        $this->sut->setCustomFilter('filter', 'value');
        $result = $this->sut->getCustomFilter('filter');

        $this->assertSame('value', $result);
    }

    /** @test */
    public function testGetCustomFilterNullAsDefaultValue()
    {
        $result = $this->sut->getCustomFilter('filter');

        $this->assertNull($result);
    }

    /** @test */
    public function testHasCustomFilter()
    {
        $result = $this->sut->hasCustomFilter('filter');
        $this->assertFalse($result);

        $this->sut->setCustomFilter('filter', 'value');
        $result = $this->sut->hasCustomFilter('filter');
        $this->assertTrue($result);
    }
}
