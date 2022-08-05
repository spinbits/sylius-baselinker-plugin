<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Unit\Rest;

use PHPUnit\Framework\TestCase;
use Spinbits\SyliusBaselinkerPlugin\Handler\HandlerInterface;
use Spinbits\SyliusBaselinkerPlugin\RequestHandler;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;
use Spinbits\SyliusBaselinkerPlugin\Rest\Response;
use Spinbits\SyliusBaselinkerPlugin\Rest\ResponseError;

class RequestHandlerTest extends TestCase
{
    private const EXPECTED_PASSWORD = 'example-pass';

    public function testItShouldRegisterHandler()
    {
        $sut = new RequestHandler(self::EXPECTED_PASSWORD);
        $handler = $this->createMock(HandlerInterface::class);

        $result = $sut->registerHandler('action', $handler);

        $this->assertNull($result);
    }

    public function testItShouldReturnSupportedActions()
    {
        $sut = new RequestHandler(self::EXPECTED_PASSWORD);
        $handler = $this->createMock(HandlerInterface::class);

        $result = $sut->supportedActions();
        $this->assertSame([], $result);

        $sut->registerHandler('action', $handler);
        $result = $sut->supportedActions();
        $this->assertSame(['action'], $result);
    }

    public function testItShouldReturnErrorResponseWhenPasswordNotPassed()
    {
        $sut = new RequestHandler(self::EXPECTED_PASSWORD);

        $input = $this->createMock(Input::class);
        $input
            ->expects($this->once())
            ->method('password')
            ->willReturn(null);

        $response = $sut->handle($input);

        $this->assertInstanceOf(ResponseError::class, $response);
        $this->assertSame([
            'error' => true,
            'error_code' => 422,
            'error_text' => "Missing password parameter",
        ], $response->content());
        $this->assertEquals(422, $response->code());
    }

    public function testItShouldReturnErrorResponseWhenWrongPasswordPassed()
    {
        $sut = new RequestHandler(self::EXPECTED_PASSWORD);

        $input = $this->createMock(Input::class);
        $input
            ->expects($this->exactly(2))
            ->method('password')
            ->willReturn('wrong-password');

        $response = $sut->handle($input);

        $this->assertInstanceOf(ResponseError::class, $response);
        $this->assertSame([
            'error' => true,
            'error_code' => 401,
            'error_text' => "Wrong password",
        ], $response->content());
        $this->assertEquals(401, $response->code());
    }


    public function testItShouldReturnErrorResponseWhenActionNotPassed()
    {
        $sut = new RequestHandler(self::EXPECTED_PASSWORD);

        $input = $this->createMock(Input::class);
        $input
            ->expects($this->exactly(2))
            ->method('password')
            ->willReturn(self::EXPECTED_PASSWORD);

        $input
            ->expects($this->exactly(1))
            ->method('action')
            ->willReturn(null);

        $response = $sut->handle($input);

        $this->assertInstanceOf(ResponseError::class, $response);
        $this->assertSame([
            'error' => true,
            'error_code' => 422,
            'error_text' => "Missing action parameter",
        ], $response->content());
        $this->assertEquals(422, $response->code());
    }

    public function testItShouldReturnErrorResponseWhenActionHandleNotExists()
    {
        $sut = new RequestHandler(self::EXPECTED_PASSWORD);

        $input = $this->createMock(Input::class);
        $input
            ->expects($this->exactly(2))
            ->method('password')
            ->willReturn(self::EXPECTED_PASSWORD);

        $input
            ->expects($this->exactly(3))
            ->method('action')
            ->willReturn('some-action');

        $response = $sut->handle($input);

        $this->assertInstanceOf(ResponseError::class, $response);
        $this->assertSame([
            'error' => true,
            'error_code' => 422,
            'error_text' => 'Handler for action "some-action" is not configured. Please use "setHandler" to map it.',
        ], $response->content());
        $this->assertEquals(422, $response->code());
    }

    public function testItShouldHandleInput()
    {
        $sut = new RequestHandler(self::EXPECTED_PASSWORD);

        $input = $this->createMock(Input::class);
        $input
            ->expects($this->exactly(2))
            ->method('password')
            ->willReturn(self::EXPECTED_PASSWORD);

        $input
            ->expects($this->exactly(3))
            ->method('action')
            ->willReturn('some-action');

        $handler = $this->createMock(HandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willReturn(['key' => 'value']);

        $sut->registerHandler('some-action', $handler);

        $response = $sut->handle($input);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame([
            'key' => 'value'
        ], $response->content());
        $this->assertEquals(200, $response->code());
    }
}
