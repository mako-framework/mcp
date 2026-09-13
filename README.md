# MCP

[![Tests](https://github.com/mako-framework/mcp/actions/workflows/tests.yml/badge.svg)](https://github.com/mako-framework/mcp/actions/workflows/tests.yml)
[![Static analysis](https://github.com/mako-framework/mcp/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/mako-framework/mcp/actions/workflows/static-analysis.yml)

MCP ([Model Context Protocol](https://modelcontextprotocol.io)) package for the [Mako Framework](https://makoframework.com).

The package is built on top of the official [MCP PHP SDK](https://github.com/modelcontextprotocol/php-sdk) and makes it easy to expose tools, resources and prompts from your Mako application.

## Requirements

* Mako 13.0+

## Installation

Install the package using the following composer command:

```
composer require mako/mcp
```

Next, publish the package configuration to your application using the following reactor command:

```
php app/reactor package:install mako/mcp
```

Finally, add the package to the list of packages in your `app/config/application.php` file:

```php
return
[
	// ...

	'packages' => [
		'cli' => [
			mako\mcp\McpPackage::class,
		],
	],

	// ...
];
```

## Usage

The MCP server can be run over `stdio` using the included reactor command or over HTTP using a controller.

### Visual Studio Code

To use the MCP server with Visual Studio Code, add the following to your `.vscode/mcp.json` file:

```json
{
	"servers": {
		"mako-app": {
			"type": "stdio",
			"command": "php",
			"args": [
				"${workspaceFolder}/app/reactor",
				"mcp:server"
			]
		}
	}
}
```

### HTTP

Serving the MCP server over HTTP requires the [PSR HTTP message bridge](https://github.com/mako-framework/psr-http-message-bridge) package along with a [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-17](https://www.php-fig.org/psr/psr-17/) implementation such as [nyholm/psr7](https://github.com/Nyholm/psr7):

```
composer require mako/psr-http-message-bridge nyholm/psr7
```

You can then serve the MCP server from a controller. Note that the following example demonstrates basic usage without authentication or any other security measures, so make sure to secure the endpoint before exposing it publicly:

```php
<?php

namespace app\http\controllers;

use mako\bridges\psr\http\message\MakoResponseHydrator;
use mako\bridges\psr\http\message\PsrServerRequestFactory;
use mako\http\Request;
use mako\http\Response;
use Mcp\Server;
use Mcp\Server\Transport\StreamableHttpTransport;
use Nyholm\Psr7\Factory\Psr17Factory;

class Mcp
{
	public function __invoke(Request $request, Response $response, Server $server): void
	{
		$psr17Factory = new Psr17Factory;

		$factory = new PsrServerRequestFactory(
			serverRequestFactory: $psr17Factory,
			uriFactory: $psr17Factory,
			streamFactory: $psr17Factory,
			uploadedFileFactory: $psr17Factory
		);

		$psrRequest = $factory->create($request);

		$psrResponse = $server->run(new StreamableHttpTransport($psrRequest));

		new MakoResponseHydrator()->hydrate($response, $psrResponse);
	}
}
```

Finally, register a route for it in your `app/http/routing/routes.php` file:

```php
<?php

use app\http\controllers\Mcp;

$routes->any('/mcp', Mcp::class);
```
