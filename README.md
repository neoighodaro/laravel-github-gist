# Simple GitHub Gist API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/neoighodaro/gist.svg?style=flat-square)](https://packagist.org/packages/neoighodaro/gist)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/neoighodaro/gist/run-tests?label=tests)](https://github.com/neoighodaro/gist/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/neoighodaro/gist/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/neoighodaro/gist/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/neoighodaro/gist.svg?style=flat-square)](https://packagist.org/packages/neoighodaro/gist)

A simple Package to work with the GitHub Gist API.

> It's by no means covering the complete API endpoints. I just needed a few endpoints and decided to make it a package and maybe flesh it out as needed. If something is missing, feel free to send a PR.

## Installation

You can install the package via composer:

```bash
composer require neoighodaro/gist
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="gist-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$gist = new Neo\Gist();
echo $gist->echoPhrase('Hello, Neo!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Neo](https://github.com/neoighodaro)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
