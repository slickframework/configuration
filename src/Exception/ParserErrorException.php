<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Exception;

use RuntimeException;
use Slick\Configuration\Exception;

/**
 * Parser Error Exception, trowed when an error occurs while parsing the
 * configuration file.
 *
 * @package Slick\Configuration\Exception
 */
class ParserErrorException extends RuntimeException implements Exception
{

}
