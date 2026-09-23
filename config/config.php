<?php

use Mcp\Capability\Discovery\DiscovererInterface;

return [
	/*
	 * ---------------------------------------------------------
	 * Default server.
	 * ---------------------------------------------------------
	 *
	 * Name of the default MCP server.
	 */
	'default' => 'main',

	/*
	 * ---------------------------------------------------------
	 * Servers.
	 * ---------------------------------------------------------
	 *
	 * server_info:
	 *
	 * name       : Name identifying the MCP server to clients
	 * version    : Version of the server implementation, not the MCP protocol version
	 * description: (optional) Description of the server
	 * icons      : (optional) Array of Mcp\Schema\Icon objects
	 * website_url: (optional) URL of the server's website
	 * title      : (optional) Human-readable display name for the server
	 *
	 * discovery:
	 *
	 * base_path    : Base directory for discovery. Defaults to the application path when set to null
	 * scan_dirs    : Directories to scan, relative to the base path
	 * exclude_dirs : Directories to exclude from discovery
	 * name_patterns: File name patterns used to select files for discovery
	 *
	 * session (only used when serving the MCP server over HTTP):
	 *
	 * cache_configuration: Cache configuration used to store sessions. Defaults to the default cache configuration when set to null
	 * prefix             : Prefix used for the session cache keys
	 * ttl                : Session time to live in seconds
	 */
	'servers' => [
		'main' => [
			'server_info' => [
				'name'    => 'Mako MCP Server',
				'version' => '1.0.0',
			],
			'discovery' => [
				'base_path'     => null,
				'scan_dirs'     => ['mcp'],
				'exclude_dirs'  => [],
				'name_patterns' => DiscovererInterface::DEFAULT_NAME_PATERNS,
			],
			'session' => [
				'cache_configuration' => null,
				'prefix'              => 'mcp-',
				'ttl'                 => 3600,
			],
		],
	],
];
