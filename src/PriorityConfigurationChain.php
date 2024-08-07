<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration;

use JetBrains\PhpStorm\Pure;
use Slick\Configuration\Common\PriorityList;
use Slick\Configuration\Driver\CommonDriverMethods;

/**
 * PriorityConfigurationChain
 *
 * @package Slick\Configuration
*/
class PriorityConfigurationChain implements ConfigurationChainInterface
{

    /**
     * @var PriorityList
     */
    private PriorityList $priorityList;

    private bool $dirty = true;

    use CommonDriverMethods {
        get as private baseGet;
        set as private baseSet;
    }

    /**
     * Creates a Priority Configuration Chain
     */
    public function __construct()
    {
        $this->priorityList = new PriorityList();
    }

    /**
     * Returns the value store with provided key or the default value.
     *
     * @param string $key The key used to store the value in configuration
     * @param mixed|null $default The default value if no value was stored.
     *
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->dirty) {
            $this->mergeValues();
        }
        return $this->baseGet($key, $default);
    }

    public function set(string $key, mixed $value): self
    {
        if ($this->dirty) {
            $this->mergeValues();
        }
        $this->baseSet($key, $value);
        return $this;
    }

    /**
     * Add a configuration driver to the chain
     *
     * The configuration driver will be placed according to its priority.
     * Highest priority will be verified first
     *
     * @param ConfigurationInterface $config
     * @param integer $priority
     *
     * @return ConfigurationChainInterface self
     */
    public function add(ConfigurationInterface $config, int $priority = 0): ConfigurationChainInterface
    {
        $this->priorityList->insert($config, $priority);
        $this->dirty = true;
        return $this;
    }

    /**
     * Returns the internal configuration driver chain
     *
     * @return PriorityList
     */
    public function priorityList(): PriorityList
    {
        return $this->priorityList;
    }

    private function mergeValues(): void
    {
        $this->dirty = false;
        $data = [];
        $elements = array_reverse($this->priorityList->asArray());
        foreach ($elements as $element) {
            $data = $this->mergeArrays($data, $element->asArray());
        }
        $this->data = $data;
    }

    /**
     * Merges two arrays recursively, overriding values from the default array with
     * values from the custom array.
     *
     * @param array<string, mixed> $default The default array.
     * @param array<string, mixed> $custom The custom array.
     *
     * @return array<string, mixed> The merged array.
     */
    private function mergeArrays(array $default, array $custom): array
    {
        $base = [];
        $names = [];
        foreach ($default as $name => $value) {
            $isPresent = array_key_exists($name, $custom);
            $names[] = $name;
            if (is_array($value) && $isPresent) {
                $base[$name] = $this->mergeArrays($value, $custom[$name]);
                continue;
            }

            $base[$name] = $isPresent ? $custom[$name] : $value;
        }

        foreach ($custom as $other => $value) {
            if (!in_array($other, $names)) {
                $base[$other] = $value;
            }
        }

        return $base;
    }
}
