<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration;

use Slick\Configuration\Driver\Environment;
use Slick\Configuration\Driver\Ini;
use Slick\Configuration\Driver\Php;
use Slick\Configuration\Exception\InvalidArgumentException;

/**
 * Configuration
 *
 * @package Slick\Configuration
*/
class Configuration
{
    /**@#+
     * Known configuration drivers
     */
    const DRIVER_INI = Ini::class;
    const DRIVER_PHP = Php::class;
    const DRIVER_ENV = Environment::class;
    /**@#- */

    private $extensionToDriver = [
        'ini' => self::DRIVER_INI,
        'php' => self::DRIVER_PHP,
    ];

    /**
     * @var array
     */
    private $options;

    /**
     * @var null|string
     */
    private $driverClass;

    /**
     * Creates a configuration factory
     *
     * @param array $options
     * @param null  $driverClass
     */
    public function __construct(array $options = [], $driverClass = null)
    {
        $this->options = $options;
        $this->driverClass = $driverClass;
    }

    public function initialize()
    {
        $reflection = new \ReflectionClass($this->driverClass());
        return $reflection->newInstanceArgs($this->options);
    }

    /**
     * Returns the driver class to be initialized
     *
     * @return mixed|null|string
     */
    private function driverClass()
    {
        if (null == $this->driverClass) {
            $file = reset($this->options);
            $this->driverClass = $this->determineDriver($file);
        }
        return $this->driverClass;
    }

    /**
     * Tries to determine the driver class based on given file
     *
     * @return mixed
     */
    private function determineDriver($file)
    {
        $exception = new InvalidArgumentException(
            "Cannot initialize the configuration driver. I could not determine " .
            "the correct driver class."
        );

        if (is_null($file)) {
            throw $exception;
        }

        $nameDivision = explode('.', $file);
        $extension = strtolower(end($nameDivision));

        if (! array_key_exists($extension, $this->extensionToDriver)) {
            throw $exception;
        }

        return $this->extensionToDriver[$extension];
    }
}