# MCP

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
use mako\mcp\McpPackage;

$packages = [
    'cli' => [
        McpPackage::class,
    ],
];
```

## Usage

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
