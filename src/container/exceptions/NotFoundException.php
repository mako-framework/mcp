<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\container\exceptions;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

/**
 * Not found exception.
 */
final class NotFoundException extends RuntimeException implements NotFoundExceptionInterface
{

}
