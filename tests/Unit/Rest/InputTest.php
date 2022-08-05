<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Rest;

use Spinbits\SyliusBaselinkerPlugin\Rest\Input;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** Class InputTest */
class InputTest extends TestCase
{
    /** @var Input */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new Input([
            'action' => 'action-name',
            'bl_pass' => 'secret-pass',
            'key1' => 'value',
            'key2' => null,
        ]);
    }

    /** @test */
    public function testHas()
    {
        $this->assertTrue($this->sut->has('key1'));
        $this->assertFalse($this->sut->has('not-existing'));
    }

    /** @test */
    public function testGet()
    {
        $this->assertSame('value', $this->sut->get('key1'));
        $this->assertSame(null, $this->sut->get('key2'));
        $this->assertSame(null, $this->sut->get('not-existing'));
    }

    /** @test */
    public function testAction()
    {
        $this->assertSame('action-name', $this->sut->action());
    }

    /** @test */
    public function testPassword()
    {
        $this->assertSame('secret-pass', $this->sut->password());
    }

    /** @test */
    public function testAll()
    {
        $this->assertSame([
            'action' => 'action-name',
            'bl_pass' => 'secret-pass',
            'key1' => 'value',
            'key2' => null,
        ], $this->sut->all());
    }
}
