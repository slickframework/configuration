<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Driver;

use Slick\Configuration\ConfigurationInterface;

/**
 * Environment
 *
 * @package Slick\Configuration\Driver
*/
class Environment implements ConfigurationInterface
{

    use CommonDriverMethods;

    public function __construct()
    {
        $envVars = getenv();
        if (!is_array($envVars)) {
            return;
        }

        foreach ($envVars as $key => $value) {
            $this->set($this->createKey($key), $value);
        }
    }

    private function createKey(string $envName): string
    {
        return trim(strtolower(str_replace('_', '.', $envName)), '.');
    }
}
