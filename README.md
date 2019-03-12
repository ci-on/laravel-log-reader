# Add Readable Log File Output to a Laravel App

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Quality Score](https://img.shields.io/scrutinizer/g/ci-on/laravel-log-reader.svg?style=flat-square)](https://scrutinizer-ci.com/g/ci-on/laravel-log-reader)
[![StyleCI](https://styleci.io/repos/175110511/shield?branch=master)](https://styleci.io/repos/175110511)

Please do not use this in production environments at the moment. This package is still a work in progress.

## Installation

You can install the package via composer:

```bash
composer require cion/laravel-log-reader
```

The package will register itself automatically. 

Optionally, you can publish the package configuration using:

```bash
php artisan vendor:publish --provider=Cion\\LaravelLogReader\\ServiceProvider
```

This will publish a file called `debug-server.php` in your `config` folder.
In the config file, you can specify the dump server host that you want to listen on, in case you want to change the default value.

## Usage

wip

## Testing

1. Copy `.env.example` to `.env` and fill in your database credentials.
2. Run `composer test`.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email mohamed@cion.agency instead of using the issue tracker.

## Credits

- [Mohamed Benhida](https://github.com/simoebenhida)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
