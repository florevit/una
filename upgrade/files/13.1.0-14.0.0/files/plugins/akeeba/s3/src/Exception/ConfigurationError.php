<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2025 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\S3\Exception;

// Protection against direct access
defined('AKEEBAENGINE') || die();

use RuntimeException;

/**
 * Configuration error
 */
abstract class ConfigurationError extends RuntimeException
{

}
