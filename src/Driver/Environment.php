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

    /**
     * @inheritdoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $data = is_array($this->data) ? $this->data : $default;
        $stored = self::getValue($key, false, $data);
        if ($stored !== false) {
            return $stored;
        }

        $value = $default;
        $fromEnvironment = getenv($this->transformKey($key));

        if ($fromEnvironment !== false) {
            $value = $fromEnvironment;
            self::setValue($key, $value, $data);
        }
        return $value;
    }

    /**
     * Transforms the provided key to an environment variable name
     *
     * @param string $key
     * @return string
     */
    private function transformKey(string $key): string
    {
        $regEx = '/(?#! splitCamelCase Rev:20140412)
            # Split camelCase "words". Two global alternatives. Either g1of2:
              (?<=[a-z])      # Position is after a lowercase,
              (?=[A-Z])       # and before an uppercase letter.
            | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
              (?=[A-Z][a-z])  # and before upper-then-lower case.
            /x';

        $words   = preg_split($regEx, $key);
        $envName = implode('_', is_array($words) ? $words : null);
        $envName = str_replace(['.', '_', '-'], '_', $envName);
        return strtoupper($envName);
    }
}
