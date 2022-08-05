<?php

/**
 * @author Marcin Hubert <hubert.m.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Model;

use ReflectionClass;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;

abstract class AbstractDto
{
    protected array $customHandlers = [];
    protected Input $input;

    public function __construct(Input $input)
    {
        $this->input = $input;
        foreach ($input->all() as $key => $value) {
            if (isset($this->customHandlers[$key])) {
                $this->customHandlers[$key]($key, $value);
                continue;
            }

            $this->{$key} = $this->cast($key, $value);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return bool|float|int|string
     */
    private function cast(string $name, $value)
    {
        $reflection = new ReflectionClass($this);
        if (!$reflection->hasProperty($name)) {
            return null;
        }
        switch ($reflection->getProperty($name)->getType()->getName()) {
            case 'bool':
                return (bool) $value;
            case 'float':
                return (float) $value;
            case 'int':
                return (int) $value;
            case 'string':
            default:
                return (string) $value;
        }
    }
}
