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
     * @var string
     */
    private $file;

    /**
     * @var null|string
     */
    private $driverClass;

    private static $paths = [
        './'
    ];

    /**
     * Creates a configuration factory
     *
     * @param string|array $options
     * @param null         $driverClass
     */
    public function __construct($options = null, $driverClass = null)
    {
        $this->file = $options;
        $this->driverClass = $driverClass;
        self::addPath(getcwd());
    }

    /**
     * Creates a ConfigurationInterface with passed arguments
     *
     * @param string|array $fileName
     * @param null         $driverClass
     *
     * @return ConfigurationInterface|PriorityConfigurationChain
     */
    public static function get($fileName, $driverClass = null)
    {
        $configuration = new Configuration($fileName, $driverClass);
        return $configuration->initialize();
    }

    /**
     * @return PriorityConfigurationChain|ConfigurationInterface
     */
    public function initialize()
    {
        $chain = new PriorityConfigurationChain();

        $options = $this->fixOptions();

        foreach ($options as $option) {
            $priority = $this->setProperties($option);
            $chain->add($this->createConfigurationDriver(), $priority);
        }

        return $chain;
    }

    /**
     * Prepends a searchable path to available paths list.
     *
     * @param string $path
     */
    public static function addPath($path)
    {
        $path = str_replace('//', '/', rtrim($path, '/'));
        if (!in_array($path, self::$paths)) {
            array_unshift(self::$paths, $path);
        }
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

    /**
     * Compose the filename with existing paths and return when match
     *
     * If no match is found the $name is returned as is;
     * If no extension is given it will add it from driver class map;
     * By default it will try to find <$name>.php file
     *
     * @param  string $name
     *
     * @return string
     */
    private function composeFileName($name)
    {
        if (is_null($name)) {
            return $name;
        }

        $ext = $this->determineExtension();
        $withExtension = $this->createName($name, $ext);

        list($found, $fileName) = $this->searchFor($name, $withExtension);

        return $found ? $fileName : $name;
    }

    /**
     * Determine the extension based on the driver class
     *
     * If there is no driver class given it will default to .php
     *
     * @return string
     */
    private function determineExtension()
    {
        $ext = 'php';
        if (in_array($this->driverClass, $this->extensionToDriver)) {
            $map = array_flip($this->extensionToDriver);
            $ext = $map[$this->driverClass];
        }
        return $ext;
    }

    /**
     * Creates the name with the extension for known names
     *
     * @param string $name
     * @param string $ext
     *
     * @return string
     */
    private function createName($name, $ext)
    {
        $withExtension = $name;
        if (!preg_match('/.*\.(ini|php)/i', $name)) {
            $withExtension = "{$name}.{$ext}";
        }
        return $withExtension;
    }

    /**
     * Search for name in the list of paths
     *
     * @param string $name
     * @param string $withExtension
     *
     * @return array
     */
    private function searchFor($name, $withExtension)
    {
        $found = false;
        $fileName = $name;

        foreach (self::$paths as $path) {
            $fileName = "{$path}/$withExtension";
            if (is_file($fileName)) {
                $found = true;
                break;
            }
        }

        return [$found, $fileName];
    }

    /**
     * Creates the configuration driver from current properties
     *
     * @return ConfigurationInterface
     */
    private function createConfigurationDriver()
    {
        $reflection = new \ReflectionClass($this->driverClass());

        /** @var ConfigurationInterface $config */
        $config = $reflection->hasMethod('__construct')
            ? $reflection->newInstanceArgs([$this->file])
            : $reflection->newInstance();
        return $config;
    }

    /**
     * Sets the file and driver class
     *
     * @param array $option
     *
     * @return int
     */
    private function setProperties($option)
    {
        $priority = isset($option[2]) ? $option[2] : 0;
        $this->driverClass = isset($option[1]) ? $option[1] : null;
        $this->file = isset($option[0]) ? $this->composeFileName($option[0]) : null;
        return $priority;
    }

    /**
     * Fixes the file for initialization
     *
     * @return array
     */
    private function fixOptions()
    {
        $options = (is_array($this->file))
            ? $this->file
            : [[$this->file]];
        return $options;
    }
}
