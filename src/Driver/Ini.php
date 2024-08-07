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
 * Ini configuration driver
 *
 * @package Slick\Configuration\Driver
 */
class Ini implements ConfigurationInterface
{

    use CommonDriverMethods;

    /**
     * @var string
     */
    private string $lastError = '';

    /**
     * Creates an ini configuration driver
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->checkFile($filePath);

        $this->loadSettings($filePath);

        if (!is_array($this->data)) {
            throw new ParserErrorException(
                "Parse error: $this->lastError"
            );
        }
    }

    private function loadSettings(string $filePath): void
    {
        set_error_handler(function (int $errorNumber, string $message): bool {
            $this->lastError = "$errorNumber: $message";
            return true;
        });
        $parsedFile = parse_ini_file($filePath, true, INI_SCANNER_TYPED);
        $this->data = is_array($parsedFile) ? $parsedFile : 0;
        restore_error_handler();
    }
}
