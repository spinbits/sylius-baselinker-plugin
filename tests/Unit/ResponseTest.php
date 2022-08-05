<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit;

use Spinbits\SyliusBaselinkerPlugin\Rest\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** Class ResponseTest */
class ResponseTest extends TestCase
{
    /** @var Response */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new Response(['key' => 'value'], 503);
    }

    /** @test */
    public function testCode()
    {
        $this->assertEquals(503, $this->sut->code());
    }


    /** @test */
    public function testContent()
    {
        $this->assertEquals(['key' => 'value'], $this->sut->content());
    }
}
