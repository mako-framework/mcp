<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\container\exceptions;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

/**
 * Not found exception.
 */
class NotFoundException extends RuntimeException implements ContainerExceptionInterface
{

}
