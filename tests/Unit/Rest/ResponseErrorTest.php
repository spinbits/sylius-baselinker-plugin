<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Rest;

use Spinbits\SyliusBaselinkerPlugin\Rest\ResponseError;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** Class ResponseErrorTest */
class ResponseErrorTest extends TestCase
{
    /** @var ResponseError */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new ResponseError('error', 401);
    }

    /** @test */
    public function testCode()
    {
        $this->assertEquals(401, $this->sut->code());
    }

    /** @test */
    public function testContent()
    {
        $this->assertEquals([
            'error' => true,
            'error_code' => 401,
            'error_text' => 'error',
        ], $this->sut->content());
    }
}
