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

    use CommonDriverMethods;

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
     * @param string $key     The key used to store the value in configuration
     * @param mixed|null $default The default value if no value was stored.
     *
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $data = is_array($this->data) ? $this->data : [];
        $stored = static::getValue($key, false, $data);

        if ($stored !== false) {
            return $stored;
        }

        foreach ($this->priorityList as $driver) {
            $value = $driver->get($key, false);
            if ($value !== false) {
                $default = $value;
                static::setValue($key, $value, $this->data);
                break;
            }
        }
        return $default;
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
}
