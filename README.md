# Add Readable Log File Output to a Laravel App

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Quality Score](https://img.shields.io/scrutinizer/g/ci-on/laravel-log-reader.svg?style=flat-square)](https://scrutinizer-ci.com/g/ci-on/laravel-log-reader)
[![StyleCI](https://styleci.io/repos/175110511/shield?branch=master)](https://styleci.io/repos/175110511)

<p align="center"><img src="https://github.com/ci-on/laravel-log-reader/blob/master/demo.png?raw=true"></p>

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

This will publish a file called `log-reader.php` in your `config` folder to adjust a few config values.

## Usage

You just need to visit `/logreader` if you didn't change `prefix` in your config

## Testing

1. Copy `.env.example` to `.env` and fill in your database credentials.
2. Run `composer test`.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email contact@mohamedbenhida.com instead of using the issue tracker.

## Credits

- [Mohamed Benhida](https://github.com/simoebenhida)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
