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
use Sylius\Component\Core\Model\ChannelInterface;

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
    public function testSetChannel()
    {
        $input = $this->createMock(Input::class);
        $channel = $this->createMock(ChannelInterface::class);

        $this->sut = new AbstractFilter($input, $channel);

        $this->assertTrue($this->sut->hasChannel());
        $this->assertInstanceOf(ChannelInterface::class, $this->sut->getChannel());
    }

    /** @test */
    public function testGetChannelNullAsDefaultValue()
    {
        $result = $this->sut->getChannel();

        $this->assertNull($result);
    }
}
