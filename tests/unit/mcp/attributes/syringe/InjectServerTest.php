<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\tests\unit\mcp\attributes\syringe;

use mako\mcp\attributes\syringe\InjectServer;
use mako\mcp\ServerFactory;
use mako\syringe\Container;
use mako\tests\TestCase;
use Mcp\Server;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use ReflectionParameter;

#[Group('unit')]
class InjectServerTest extends TestCase
{
	/**
	 *
	 */
	public function testInjectServerWithNull(): void
	{
		$server = Mockery::mock(Server::class);

		$serverFactory = Mockery::mock(ServerFactory::class);

		$serverFactory->shouldReceive('create')->once()->with(null)->andReturn($server);

		$container = Mockery::mock(Container::class);

		$container->shouldReceive('get')->once()->with(ServerFactory::class)->andReturn($serverFactory);

		$injector = new InjectServer(null);

		$reflection = Mockery::mock(ReflectionParameter::class);

		$this->assertInstanceOf(Server::class, $injector->getParameterValue($container, $reflection));
	}

	/**
	 *
	 */
	public function testInjectServerWithName(): void
	{
		$server = Mockery::mock(Server::class);

		$serverFactory = Mockery::mock(ServerFactory::class);

		$serverFactory->shouldReceive('create')->once()->with('foobar')->andReturn($server);

		$container = Mockery::mock(Container::class);

		$container->shouldReceive('get')->once()->with(ServerFactory::class)->andReturn($serverFactory);

		$injector = new InjectServer('foobar');

		$reflection = Mockery::mock(ReflectionParameter::class);

		$this->assertInstanceOf(Server::class, $injector->getParameterValue($container, $reflection));
	}
}
