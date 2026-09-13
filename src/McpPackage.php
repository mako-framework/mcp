<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp;

use mako\application\Package;
use mako\mcp\console\commands\Server;
use mako\mcp\services\McpService;
use Override;

/**
 * MCP package.
 */
class McpPackage extends Package
{
	/**
	 * {@inheritDoc}
	 */
	#[Override]
	final protected string $packageName = 'mako/mcp';

	/**
	 * {@inheritDoc}
	 */
	#[Override]
	protected array $services = [
		'core' => [
			McpService::class,
		],
	];

	/**
	 * {@inheritDoc}
	 */
	#[Override]
	final protected array $commands = [
		'mcp:server' => Server::class,
	];
}
