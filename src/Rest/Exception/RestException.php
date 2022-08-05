<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Rest\Exception;

use Exception;
use Throwable;

abstract class RestException extends Exception
{
    public const STATUS_CODE = 500;

    /**
     *
     * @param string         $message
     * @param Throwable|null $prev
     */
    public function __construct(string $message, Throwable $prev = null)
    {
        parent::__construct($message, static::STATUS_CODE, $prev);
    }
}
