<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp;

use mako\application\Application;
use mako\cache\CacheManager;
use mako\cache\psr16\SimpleCache;
use mako\mcp\container\Container;
use mako\mcp\exceptions\McpException;
use Mcp\Server;
use Mcp\Server\Session\Psr16SessionStore;
use Psr\Log\LoggerInterface;

use function sprintf;

/**
 * Server factory.
 */
class ServerFactory
{
	/**
	 * Constructor.
	 */
	public function __construct(
		protected string $default,
		protected array $servers,
		protected Application $application,
	) {
	}

	/**
	 * Returns an MCP server instance.
	 */
	public function create(?string $name = null): Server
	{
		$name ??= $this->default;

		$config = $this->servers[$name] ?? throw new McpException(
			sprintf('The [ %s ] MCP server configuration does not exist.', $name)
		);

		$container = $this->application->getContainer();

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
			$config['discovery']['base_path'] ?? $this->application->getPath(),
			$config['discovery']['scan_dirs'],
			$config['discovery']['exclude_dirs'],
			null,
			$config['discovery']['name_patterns']
		)
		->setContainer(new Container($container));

		// Set logger if we have one in the container

		if ($container->has(LoggerInterface::class)) {
			$builder->setLogger($container->get(LoggerInterface::class));
		}

		// Set a session store if we are in a web context

		if (!$this->application->isCommandLine()) {
			$builder->setSession(new Psr16SessionStore(
				new SimpleCache(
					$container->get(CacheManager::class)
					->getInstance($config['session']['cache_configuration'] ?? null)
				),
				prefix: $config['session']['prefix'] ?? "mcp-{$name}-",
				ttl: $config['session']['ttl'] ?? 3600
			));
		}

		// Build and return the server

		return $builder->build();
	}
}
