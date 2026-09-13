<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\container;

use mako\mcp\container\exceptions\ContainerException;
use mako\mcp\container\exceptions\NotFoundException;
use mako\syringe\Container as MakoContainer;
use Override;
use Psr\Container\ContainerInterface;
use ReflectionException;
use Throwable;

use function class_exists;
use function ltrim;
use function str_contains;

/**
 * Container.
 */
final class Container implements ContainerInterface
{
	/**
	 * Constructor.
	 */
	public function __construct(
		protected MakoContainer $container
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	#[Override]
	public function get(string $id): mixed
	{
		try {
			return $this->container->get($id);
		}
		catch (Throwable $e) {
			if (
				$e instanceof ReflectionException
				&& str_contains($e->getMessage(), 'Class "' . ltrim($id, '\\') . '" does not exist')
			) {
				throw new NotFoundException($e->getMessage(), previous: $e);
			}

			throw new ContainerException($e->getMessage(), previous: $e);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	#[Override]
	public function has(string $id): bool
	{
		return $this->container->has($id) || class_exists($id);
	}
}
