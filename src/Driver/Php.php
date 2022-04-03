<?php

/**
 * This file is part of Configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Driver;

use Slick\Configuration\ConfigurationInterface;
use Slick\Configuration\Exception\ParserErrorException;

/**
 * Php Configuration driver
 *
 * @package Slick\Configuration\Driver
 */
class Php implements ConfigurationInterface
{
    /**
     * @var string
     */
    private string $filePath;

    use CommonDriverMethods;

    /**
     * Creates a Php configuration driver
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->checkFile($filePath);

        $this->filePath = $filePath;
        $this->loadSettings();

        if (!is_array($this->data)) {
            throw new ParserErrorException(
                "Configuration file $this->filePath could not be parse as an array. ".
                "PHP Settings file should be a script that returns an array."
            );
        }
    }

    /**
     * Loads settings from php array in file
     */
    private function loadSettings()
    {
        ob_start();
        $this->data = include $this->filePath;
        ob_end_clean();
    }
}
