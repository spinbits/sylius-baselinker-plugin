<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Rest;

/** Response */
class Response implements ResponseInterface
{
    private const DEFAULT_CODE = 200;

    /** @var int */
    private $code;

    /** @var array<mixed> */
    private $content;

    /**
     * @param array<mixed> $content
     * @param int          $code
     */
    public function __construct(array $content, $code = self::DEFAULT_CODE)
    {
        $this->code = $code;
        $this->content = $content;
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
