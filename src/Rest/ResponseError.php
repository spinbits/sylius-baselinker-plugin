<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Rest;

/** ResponseError */
class ResponseError implements ResponseInterface
{
    /** @var int */
    private $code;

    /** @var array<mixed> */
    private $content;

    /**
     * @param string $message
     * @param int    $code
     */
    public function __construct(string $message, int $code)
    {
        $this->code = $code;
        $this->content = [
            'error' => true,
            'error_code' => $code,
            'error_text' => $message,
        ];
    }

    /**
     * @return int
     */
    public function code(): int
    {
        return $this->code;
    }

    /**
     * @return array<mixed>
     */
    public function content(): array
    {
        return $this->content;
    }
}
