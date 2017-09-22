<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration;

use Slick\Configuration\Driver\CommonDriverMethods;

/**
 * PriorityConfigurationChain
 *
 * @package Slick\Configuration
*/
class PriorityConfigurationChain implements ConfigurationChainInterface
{

    /**
     * @var \SplPriorityQueue|ConfigurationInterface[]
     */
    private $queue;

    use CommonDriverMethods;

    /**
     * Creates a Priority Configuration Chain
     */
    public function __construct()
    {
        $this->queue = new \SplPriorityQueue();
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
        $stored = static::getValue($key, false, $this->data);
        if ($stored !== false) {
            return $stored;
        }

        foreach ($this->queue as $driver) {
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
    public function add(ConfigurationInterface $config, $priority = 0)
    {
        $this->queue->insert($config, $priority);
        return $this;
    }

    /**
     * Returns the internal configuration driver chain
     *
     * @return ConfigurationInterface[]|\SplPriorityQueue
     */
    public function queue()
    {
        return $this->queue;
    }
}