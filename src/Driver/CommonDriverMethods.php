<?php

/**
 * This file is part of Configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Driver;

use Slick\Configuration\Exception\FileNotFoundException;

/**
 * Common Driver Methods Trait
 *
 * @package Slick\Configuration\Driver
 */
trait CommonDriverMethods
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Checks if provided file exists
     *
     * @param string $file
     *
     * @throws FileNotFoundException if provided file does not exists
     */
    protected function checkFile($file)
    {
        if (! is_file($file)) {
            throw new FileNotFoundException(
                "Configuration file {$file} could not be found."
            );
        }
    }

    /**
     * Returns the value store with provided key or the default value.
     *
     * @param string $key     The key used to store the value in configuration
     * @param mixed  $default The default value if no value was stored.
     *
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get($key, $default = null)
    {
        return static::getValue($key, $default, $this->data);
    }

    /**
     * Set/Store the provided value with a given key.
     *
     * @param string $key   The key used to store the value in configuration.
     * @param mixed  $value The value to store under the provided key.
     *
     * @return CommonDriverMethods|self Self instance for method call chains.
     */
    public function set($key, $value)
    {
        static::setValue($key, $value, $this->data);
        return $this;
    }

    /**
     * Recursive method to parse dot notation keys and retrieve the value
     *
     * @param string $key     The key/index to search
     * @param mixed  $default The value if key doesn't exists
     * @param array  $data    The data to search
     *
     * @return mixed The stored value or the default value if key
     *               or index was not found.
     */
    public static function getValue($key, $default, $data)
    {
        $parts = explode('.', $key);
        $first = array_shift($parts);
        if (isset($data[$first])) {
            if (count($parts) > 0) {
                $newKey = implode('.', $parts);
                return static::getValue($newKey, $default, $data[$first]);
            }
            $default = $data[$first];
        }
        return $default;
    }
    /**
     * Recursive method to parse dot notation keys and set the value
     *
     * @param string $key   The key used to store the value in configuration.
     * @param mixed  $value The value to store under the provided key.
     * @param array  $data  The data to search
     */
    public static function setValue($key, $value, &$data)
    {
        $parts = explode('.', $key);
        $first = array_shift($parts);
        if (count($parts) > 0) {
            $newKey = implode('.', $parts);
            if (!array_key_exists($first, $data)) {
                $data[$first] = array();
            }
            static::setValue($newKey, $value, $data[$first]);
            return;
        }
        $data[$first] = $value;
    }
}
