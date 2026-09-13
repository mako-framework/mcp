<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\tests\unit\mcp\container;

use mako\mcp\container\Container;
use mako\syringe\Container as Syringe;
use mako\tests\TestCase;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use RuntimeException;

#[Group('unit')]
class ContainerTest extends TestCase
{
	/**
	 *
	 */
	public function testHasWithNonExistingClass(): void
	{
		$syringe = Mockery::mock(Syringe::class);

		$syringe->shouldReceive('has')
		->once()
		->with(FooBar::class)
		->andReturn(false);

		$container = new Container($syringe);

		$this->assertFalse($container->has(FooBar::class));
	}

	/**
	 *
	 */
	public function testHasWithExistingClass(): void
	{
		$syringe = Mockery::mock(Syringe::class);

		$syringe->shouldReceive('has')
		->once()
		->with(static::class)
		->andReturn(false);

		$container = new Container($syringe);

		$this->assertTrue($container->has(static::class));
	}

	/**
	 *
	 */
	public function testGetWithNonExistingClass(): void
	{
		$this->expectException(NotFoundExceptionInterface::class);

		$syringe = Mockery::mock(Syringe::class);

		$syringe->shouldReceive('get')
		->once()
		->with(Foo::class)
		->andThrow(
			ReflectionException::class,
			'Class "' . Foo::class . '" does not exist'
		);

		new Container($syringe)->get(Foo::class);
	}

	/**
	 *
	 */
	public function testGetWithGenericException(): void
	{
		$this->expectException(ContainerExceptionInterface::class);

		$syringe = Mockery::mock(Syringe::class);

		$syringe->shouldReceive('get')
		->once()
		->with(static::class)
		->andThrow(RuntimeException::class);

		new Container($syringe)->get(static::class);
	}
}
