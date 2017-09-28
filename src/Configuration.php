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
final class Configuration
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
    private $file;

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
        $this->file = $options;
        $this->driverClass = $driverClass;
    }

    /**
     * @return PriorityConfigurationChain|ConfigurationInterface
     */
    public function initialize()
    {
        $current = 110;
        $chain = new PriorityConfigurationChain();
        $options = is_array(reset($this->file))
            ? $this->file
            : [$this->file];

        foreach ($options as $option) {

            $priority          = isset($option[2]) ? $option[2] : $current;
            $this->driverClass = isset($option[1]) ? $option[1] : null;
            $this->file        = isset($option[0]) ? $option[0] : [];

            $reflection = new \ReflectionClass($this->driverClass());
            /** @var ConfigurationInterface $config */
            $config = $reflection->hasMethod('__construct')
                ? $reflection->newInstanceArgs([$this->file])
                : $reflection->newInstance();
            $chain->add($config, $priority -= 10);
            $current = $priority;
        }

        return $chain;
    }

    /**
     * Returns the driver class to be initialized
     *
     * @return mixed|null|string
     */
    private function driverClass()
    {
        if (null == $this->driverClass) {
            $this->driverClass = $this->determineDriver($this->file);
        }
        return $this->driverClass;
    }

    /**
     * Tries to determine the driver class based on given file
     *
     * @param string $file
     * @return mixed
     */
    private function determineDriver($file)
    {
        $exception = new InvalidArgumentException(
            "Cannot initialize the configuration driver. I could not determine " .
            "the correct driver class."
        );

        if (is_null($file) || ! is_string($file)) {
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