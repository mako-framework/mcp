<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\container\exceptions;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

/**
 * Container exception.
 */
class ContainerException extends RuntimeException implements ContainerExceptionInterface
{

}
