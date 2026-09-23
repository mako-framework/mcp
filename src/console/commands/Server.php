<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\console\commands;

use mako\cli\input\arguments\Argument;
use mako\cli\input\arguments\PositionalArgument;
use mako\mcp\ServerFactory;
use mako\reactor\attributes\CommandArguments;
use mako\reactor\attributes\CommandDescription;
use mako\reactor\Command;
use Mcp\Server\Transport\StdioTransport;

/**
 * MCP CLI Server.
 */
#[CommandDescription('Starts an MCP server using the stdio transport.')]
#[CommandArguments(
	new PositionalArgument('server', 'Name of the MCP server to run', Argument::IS_OPTIONAL)
)]
final class Server extends Command
{
	/**
	 * Starts an MCP server.
	 */
	public function execute(ServerFactory $serverFactory, ?string $server = null): void
	{
		$serverFactory->create($server)->run(new StdioTransport);
	}
}
