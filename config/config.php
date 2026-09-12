<?php

use Mcp\Capability\Discovery\DiscovererInterface;

return [
	/*
	 * ---------------------------------------------------------
	 * Server info.
	 * ---------------------------------------------------------
	 *
	 * name       : Name identifying the MCP server to clients.
     * version    : Version of the server implementation, not the MCP protocol version.
	 * description: Optional description of the server.
     * icons      : Optional array of Mcp\Schema\Icon objects.
     * website_url: Optional URL of the server's website.
     * title      : Optional human-readable display name for the server.
	 */
	'server_info' => [
		'name'    => 'Mako MCP Server',
		'version' => '1.0.0',
	],

	/*
	 * ---------------------------------------------------------
	 * Discovery.
	 * ---------------------------------------------------------
	 *
	 * base_path    : Base directory for discovery. Defaults to the application path when set to null.
     * scan_dirs    : Directories to scan, relative to the base path.
     * exclude_dirs : Directories to exclude from discovery.
     * name_patterns: File name patterns used to select files for discovery.
	 */
	'discovery' => [
		'base_path'     => null,
		'scan_dirs'     => ['.'],
		'exclude_dirs'  => [],
		'name_patterns' => DiscovererInterface::DEFAULT_NAME_PATERNS,
	],
];
