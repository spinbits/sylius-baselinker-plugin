<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Rest;

/** Input */
class Input
{
    private const POST_PASSWORD_FIELD = 'bl_pass';
    private const POST_ACTION_FIELD = 'action';

    private array $parameters;

    /**
     * @param array<string, mixed> $postData
     */
    public function __construct(array $postData)
    {
        $this->parameters = $postData;
    }

    /**
     * @return string|null
     */
    public function action(): ?string
    {
        return $this->get(self::POST_ACTION_FIELD);
    }

    /**
     * @return string|null
     */
    public function password(): ?string
    {
        return $this->get(self::POST_PASSWORD_FIELD);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->parameters[$key] : $default;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }
}
