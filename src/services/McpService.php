<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\services;

use mako\application\services\Service;
use mako\application\web\Application as WebApplication;
use mako\cache\CacheManager;
use mako\cache\psr16\SimpleCache;
use mako\config\Config;
use mako\mcp\container\Container;
use Mcp\Server;
use Mcp\Server\Session\Psr16SessionStore;
use Override;
use Psr\Log\LoggerInterface;

use function array_key_exists;

/**
 * MCP service.
 */
class McpService extends Service
{
	/**
	 * Resolves context specific directories.
	 */
	protected static function resolveContextualDirs(array $dirs, string $contextKey): array
	{
		if (array_key_exists('web', $dirs) || array_key_exists('cli', $dirs)) {
			return $dirs[$contextKey] ?? [];
		}

		return $dirs;
	}

	/**
	 * {@inheritDoc}
	 */
	#[Override]
	public function register(): void
	{
		$app = $this->app;

		$this->container->registerSingleton(
			Server::class,
			static function ($container) use ($app) {
				$config = $container->get(Config::class)->get('mako-mcp::config');

				$isWebApplication = $app instanceof WebApplication;

				// Resolve scan and exclude directories, allowing context specific configuration

				$contextKey = $isWebApplication ? 'web' : 'cli';

				$scanDirs = static::resolveContextualDirs($config['discovery']['scan_dirs'], $contextKey);

				$excludeDirs = static::resolveContextualDirs($config['discovery']['exclude_dirs'], $contextKey);

				// Set up basic server settings

				$builder = Server::builder()
				->setServerInfo(
					$config['server_info']['name'],
					$config['server_info']['version'],
					$config['server_info']['description'] ?? null,
					$config['server_info']['icons'] ?? null,
					$config['server_info']['website_url'] ?? null,
					$config['server_info']['title'] ?? null,
				)
				->setDiscovery(
					$config['discovery']['base_path'] ?? $app->getPath(),
					$scanDirs,
					$excludeDirs,
					null,
					$config['discovery']['name_patterns']
				)
				->setContainer(new Container($container));

				// Set logger if we have one in the container

				if ($container->has(LoggerInterface::class)) {
					$builder->setLogger($container->get(LoggerInterface::class));
				}

				// Set a session store if we are in a web context

				if ($isWebApplication) {
					$builder->setSession(new Psr16SessionStore(
						new SimpleCache(
							$container->get(CacheManager::class)
							->getInstance($config['session']['cache_configuration'] ?? null)
						),
						prefix: $config['session']['prefix'] ?? 'mcp-',
						ttl: $config['session']['ttl'] ?? 3600
					));
				}

				// Build and return the server

				return $builder->build();
			}
		);
	}
}
