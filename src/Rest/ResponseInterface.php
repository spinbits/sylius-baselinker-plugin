<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Rest;

/** ResponseInterface */
interface ResponseInterface
{
    /**
     * @return int
     */
    public function code(): int;

    /**
     * @return array<mixed>
     */
    public function content(): array;
}
