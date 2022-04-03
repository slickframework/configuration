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
 * File Not Found Exception, trowed when trying to load a file that
 * does not exist.
 *
 * @package Slick\Configuration\Exception
 */
class FileNotFoundException extends RuntimeException implements Exception
{

}
