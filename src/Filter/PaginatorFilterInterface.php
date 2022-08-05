<?php

/**
 * @author Marcin Hubert <hubert.m.j@gmail.com>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Filter;

interface PaginatorFilterInterface
{
    public function getPage(): int;
    public function getLimit(): int;
}
