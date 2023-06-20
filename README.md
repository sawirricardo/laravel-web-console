# Web Console for your Laravel Apps, without the terminal

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sawirricardo/laravel-web-console.svg?style=flat-square)](https://packagist.org/packages/sawirricardo/laravel-web-console)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sawirricardo/laravel-web-console/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sawirricardo/laravel-web-console/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sawirricardo/laravel-web-console/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sawirricardo/laravel-web-console/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sawirricardo/laravel-web-console.svg?style=flat-square)](https://packagist.org/packages/sawirricardo/laravel-web-console)

Ever got into a situation where you need to access your app's terminal, but you don't have access to your computer, so you cannot open your terminal. Fret not, this package wants to address that and helps you to access your app's terminal from the web!

Think of your hosting's console, but in your laravel's app.

## Support us

nvesting on this package is defintely a good move from you. You can support by donating to:

PayPal https://www.paypal.com/paypalme/sawirricardo.
BCA 8330123584

## Installation

You can install the package via composer:

```bash
composer require sawirricardo/laravel-web-console
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-web-console-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-web-console-views"
```

## Usage

In your `routes/web.php`

```php
Route::webconsole(middleware: ['auth']);
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

-   [Ricardo Sawir](https://github.com/sawirricardo)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
