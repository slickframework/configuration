<?php

/**
 * This file is part of Configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration;

/**
 * ConfigurationChainInterface
 *
 * @package Slick\Configuration
 */
interface ConfigurationChainInterface extends ConfigurationInterface
{

    /**
     * Add a configuration driver to the chain
     *
     * The configuration driver will be placed according to its priority.
     * Highest priority will be verified first
     *
     * @param ConfigurationInterface $config
     * @param integer                $priority
     *
     * @return ConfigurationChainInterface self
     */
    public function add(ConfigurationInterface $config, $priority = 0);
}
