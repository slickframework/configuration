<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration;

use ReflectionClass;
use ReflectionException;
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
     * @var class-string
     */
    const DRIVER_INI = Ini::class;
    const DRIVER_PHP = Php::class;
    const DRIVER_ENV = Environment::class;
    /**@#- */

    /** @var array<class-string>|string[]  */
    private array $extensionToDriver = [
        'ini' => self::DRIVER_INI,
        'php' => self::DRIVER_PHP,
    ];

    /**
     * @var string|array<int|string, mixed>|null
     */
    private string|array|null $file;

    /**
     * @var null|class-string
     */
    private ?string $driverClass;

    /** @var array<string>|string[]  */
    private static array $paths = [
        './'
    ];

    /**
     * @var null|ConfigurationInterface
     */
    private static ?ConfigurationInterface $instance = null;

    /**
     * Creates a configuration factory
     *
     * @param array<int|string, mixed>|string|null $options
     * @param null|class-string                    $driverClass
     */
    public function __construct(array|string $options = null, ?string $driverClass = null)
    {
        $this->file = $options;
        $this->driverClass = $driverClass;
        $path = getcwd();
        self::addPath(is_string($path) ? $path : './');
    }

    /**
     * Returns the last ConfigurationInterface
     *
     * If there is no configuration created it will use passed arguments to create one
     *
     * @param array<int|string, mixed>|string $fileName
     * @param null|class-string $driverClass
     *
     * @return PriorityConfigurationChain|ConfigurationInterface|null
     * @throws ReflectionException
     */
    public static function get(
        array|string $fileName,
        ?string $driverClass = null
    ): PriorityConfigurationChain|ConfigurationInterface|null {
        if (self::$instance === null) {
            self::$instance = self::create($fileName, $driverClass);
        }
        return self::$instance;
    }

    /**
     * Creates a ConfigurationInterface with passed arguments
     *
     * @param array<int|string, mixed>|string $fileName
     * @param null|class-string $driverClass
     *
     * @return ConfigurationInterface|PriorityConfigurationChain
     * @throws ReflectionException
     */
    public static function create(
        array|string $fileName,
        ?string $driverClass = null
    ): PriorityConfigurationChain|ConfigurationInterface {
        $configuration = new Configuration($fileName, $driverClass);
        return $configuration->initialize();
    }

    /**
     * @return PriorityConfigurationChain|ConfigurationInterface
     * @throws ReflectionException
     */
    public function initialize(): PriorityConfigurationChain|ConfigurationInterface
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
    public static function addPath(string $path): void
    {
        if (!in_array($path, self::$paths)) {
            array_unshift(self::$paths, $path);
        }
    }

    /**
     * Returns the driver class to be initialized
     *
     * @return class-string
     */
    private function driverClass(): string
    {
        if (null == $this->driverClass) {
            $fromArray = is_array($this->file) && isset($this->file[1]) ? $this->file[1] : null;
            $name = is_array($this->file) ? $fromArray : $this->file;
            $this->driverClass = $this->determineDriver($name);
        }
        return $this->driverClass;
    }

    /**
     * Tries to determine the driver class based on given file
     *
     * @param string|null $file
     * @return mixed
     */
    private function determineDriver(?string $file): mixed
    {
        $exception = new InvalidArgumentException(
            "Cannot initialize the configuration driver. I could not determine ".
            "the correct driver class."
        );

        if (is_null($file)) {
            throw $exception;
        }

        $nameDivision = explode('.', $file);
        $extension = strtolower(end($nameDivision));

        if (!array_key_exists($extension, $this->extensionToDriver)) {
            throw $exception;
        }

        return $this->extensionToDriver[$extension];
    }

    /**
     * Compose the filename with existing paths and return when match
     *
     * If not matched the $name is returned as is;
     * If no extension provided it will add it from driver class map;
     * By default it will try to find <$name>.php file
     *
     * @param null|string $name
     *
     * @return string|null
     */
    private function composeFileName(?string $name): ?string
    {
        if (is_null($name)) {
            return null;
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
    private function determineExtension(): string
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
    private function createName(string $name, string $ext): string
    {
        $withExtension = $name;
        if (!preg_match('/.*\.(ini|php)/i', $name)) {
            $withExtension = "$name.$ext";
        }
        return $withExtension;
    }

    /**
     * Search for name in the list of paths
     *
     * @param string $name
     * @param string $withExtension
     *
     * @return array{bool, string}
     */
    private function searchFor(string $name, string $withExtension): array
    {
        $found = false;
        $fileName = $name;

        foreach (self::$paths as $path) {
            $fileName = "$path/$withExtension";
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
     * @throws ReflectionException
     */
    private function createConfigurationDriver(): ConfigurationInterface
    {
        $reflection = new ReflectionClass($this->driverClass());

        /** @var ConfigurationInterface $config */
        $config = $reflection->hasMethod('__construct')
            ? $reflection->newInstanceArgs([$this->file])
            : $reflection->newInstance();
        return $config;
    }

    /**
     * Sets the file and driver class
     *
     * @param array{?string, ?class-string, ?int} $option
     *
     * @return int
     */
    private function setProperties(array $option): int
    {
        $priority = $option[2] ?? 0;
        $this->driverClass = $option[1] ?? null;
        $this->file = isset($option[0]) ? $this->composeFileName($option[0]) : null;
        return $priority;
    }

    /**
     * Fixes the file for initialization
     *
     * @return array<int|string, mixed>
     */
    private function fixOptions(): array
    {
        return (is_array($this->file))
            ? $this->file
            : [[$this->file]];
    }
}
