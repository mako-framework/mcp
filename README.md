# MCP

[![Tests](https://github.com/mako-framework/mcp/actions/workflows/tests.yml/badge.svg)](https://github.com/mako-framework/mcp/actions/workflows/tests.yml)
[![Static analysis](https://github.com/mako-framework/mcp/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/mako-framework/mcp/actions/workflows/static-analysis.yml)

MCP ([Model Context Protocol](https://modelcontextprotocol.io)) package for the [Mako Framework](https://makoframework.com).

The package is built on top of the official [MCP PHP SDK](https://github.com/modelcontextprotocol/php-sdk) and makes it easy to expose tools, resources and prompts from your Mako application.

## Requirements

* Mako ^13.0

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

> Register the package under `cli` if you only want to serve MCP servers over `stdio`, under `web` if you only want to serve them over HTTP, or under `core` if you want to be able to serve them over both.

## Usage

An MCP server can be run over `stdio` using the included reactor command or over HTTP using a controller.

```
php app/reactor mcp:server         # Runs the default server
php app/reactor mcp:server admin   # Runs the "admin" server
```

### Defining tools, resources and prompts

Tools, resources and prompts are automatically discovered, so all you have to do is create your classes and decorate the methods with the appropriate attributes from the MCP PHP SDK:

```php
<?php

namespace app\mcp\tools;

use Mcp\Capability\Attribute\McpTool;

use function rand;

class WeatherTool
{
	#[McpTool(name: 'get_weather', description: 'Get the current weather for a city')]
	public function getWeather(string $city): array
	{
		return ['city' => $city, 'temperature' => rand(-30, 30), 'unit' => 'celsius'];
	}
}
```

> The directories that are scanned during auto discovery can be configured in the published package configuration file.

### Multiple servers

The package supports running multiple MCP servers, each with its own set of tools, resources and prompts. Servers are defined in the published package configuration file:

```php
'servers' => [
	'main' => [
		// ...
	],
	'admin' => [
		// ...
	],
],
```

Type hinting the `Server` class will inject the default server. If you want to inject a named server then you can use the `InjectServer` attribute:

```php
use mako\mcp\attributes\syringe\InjectServer;
use Mcp\Server;

public function __construct(
	#[InjectServer('admin')] protected Server $server
) {
}
```

You can also create server instances programmatically using the `ServerFactory`:

```php
$server = $serverFactory->create('admin');
```

### Visual Studio Code

To use an MCP server with Visual Studio Code, add the following to your `.vscode/mcp.json` file:

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

> To run a named server instead of the default one, add the server name to the `args` array (e.g. `"mcp:server", "admin"`).

### HTTP

Serving an MCP server over HTTP requires the [PSR HTTP message bridge](https://github.com/mako-framework/psr-http-message-bridge) package along with a [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-17](https://www.php-fig.org/psr/psr-17/) implementation such as [nyholm/psr7](https://github.com/Nyholm/psr7):

```
composer require mako/psr-http-message-bridge nyholm/psr7
```

Sessions are stored using the cache when serving an MCP server over HTTP, so make sure that the `CacheService` is enabled in the services section of your `app/config/application.php` file.

You can then serve an MCP server from a controller. Note that the following example demonstrates basic usage without authentication or any other security measures, so make sure to secure the endpoint before exposing it publicly:

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

		new MakoResponseHydrator()->hydrate($response, $psrResponse, stream: true);
	}
}
```

> The example above serves the default server. To serve a named server, use the `InjectServer` attribute or the `ServerFactory` as shown in the multiple servers section.

Finally, register a route for it in your `app/http/routing/routes.php` file:

```php
<?php

use app\http\controllers\Mcp;

$routes->all('/mcp', Mcp::class);
```
