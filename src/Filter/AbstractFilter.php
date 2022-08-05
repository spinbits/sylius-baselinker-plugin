<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Filter;

use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

class AbstractFilter
{
    protected Input $input;
    private array $customFilter = [];

    public function __construct(Input $input)
    {
        $this->input = $input;
    }

    protected function get(string $parameter, $default = null)
    {
        return $this->input->get($parameter, $default);
    }

    public function hasCustomFilter(string $filterName): bool
    {
        return isset($this->customFilter[$filterName]);
    }

    public function setCustomFilter(string $filterName, $value): void
    {
        $this->customFilter[$filterName] = $value;
    }

    public function getCustomFilter(string $filterName, $default = null)
    {
        return $this->customFilter[$filterName] ?? $default;
    }
}
