<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\attributes\syringe;

use Attribute;
use mako\mcp\ServerFactory;
use mako\syringe\attributes\InjectorInterface;
use mako\syringe\Container;
use Mcp\Server;
use Override;
use ReflectionParameter;

/**
 * Server injector.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class InjectServer implements InjectorInterface
{
	/**
	 * Constructor.
	 */
	public function __construct(
		protected ?string $name = null
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	#[Override]
	public function getParameterValue(Container $container, ReflectionParameter $parameter): Server
	{
		return $container->get(ServerFactory::class)->create($this->name);
	}
}
