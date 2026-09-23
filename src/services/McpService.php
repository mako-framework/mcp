<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\services;

use mako\application\services\Service;
use mako\config\Config;
use mako\mcp\ServerFactory;
use Mcp\Server;
use Override;

/**
 * MCP service.
 */
class McpService extends Service
{
	/**
	 * {@inheritDoc}
	 */
	#[Override]
	public function register(): void
	{
		$app = $this->app;

		// Register server factory

		$this->container->registerSingleton(
			ServerFactory::class,
			static function ($container) use ($app) {
				$config = $container->get(Config::class)->get('mako-mcp::config');

				return new ServerFactory($config['default'], $config['servers'], $app);
			}
		);

		// Register default server

		$this->container->register(
			Server::class,
			static fn ($container) => $container->get(ServerFactory::class)->create()
		);
	}
}
