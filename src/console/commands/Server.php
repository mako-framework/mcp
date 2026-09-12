<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\mcp\console\commands;

use mako\reactor\attributes\CommandDescription;
use mako\reactor\Command;
use Mcp\Server as McpServer;
use Mcp\Server\Transport\StdioTransport;

/**
 * MCP CLI Server.
 */
#[CommandDescription('Starts the MCP server using the stdio transport.')]
class Server extends Command
{
	/**
	 * Run the MCP server.
	 */
	public function execute(McpServer $server): void
	{
		$server->run(new StdioTransport);
	}
}
