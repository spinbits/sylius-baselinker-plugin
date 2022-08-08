<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Handler;

use Spinbits\SyliusBaselinkerPlugin\Handler\FileVersionActionHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

/** Class FileVersionActionHandlerTest */
class FileVersionActionHandlerTest extends TestCase
{
    /** @test */
    public function testHandle()
    {
        $sut = new FileVersionActionHandler();
        $input = $this->createMock(Input::class);

        $result = $sut->handle($input);

        $this->assertSame([
            'platform' => "Sylius by spinbits",
            'version' => "4.0.0",
            'standard' => 4,
        ], $result);
    }
}
