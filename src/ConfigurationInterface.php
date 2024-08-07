<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration;

/**
 * ConfigurationInterface, defines a configuration driver
 *
 * @package Slick\Configuration
 */
interface ConfigurationInterface
{

    /**
     * Returns the value store with provided key or the default value.
     *
     * @param string $key     The key used to store the value in configuration
     * @param mixed|null $default The default value if not found
     *
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set/Store the provided value with a given key.
     *
     * @param string $key   The key used to store the value in configuration.
     * @param mixed  $value The value to store under the provided key.
     *
     * @return ConfigurationInterface Self instance for method call chains.
     */
    public function set(string $key, mixed $value): ConfigurationInterface;

    /**
     * Returns the settings values as an associative array
     *
     * @return array<string, mixed>
     */
    public function asArray(): array;
}
