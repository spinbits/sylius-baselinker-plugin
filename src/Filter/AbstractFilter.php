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
use Sylius\Component\Core\Model\ChannelInterface;

class AbstractFilter
{
    protected Input $input;
    private ?ChannelInterface $channel;

    public function __construct(Input $input, ChannelInterface $channel = null)
    {
        $this->input = $input;
        $this->channel = $channel;
    }

    protected function get(string $parameter, mixed $default = null): mixed
    {
        return $this->input->get($parameter, $default);
    }

    public function hasChannel(): bool
    {
        return $this->channel !== null;
    }

    public function getChannel(): ?ChannelInterface
    {
        return $this->channel;
    }
}
