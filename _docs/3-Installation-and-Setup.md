# 3. Installation

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command `composer require arcanedev/no-captcha`.

Or by adding the package to your `composer.json`

```json
{
    "require": {
        "arcanedev/no-captcha": "~3.0"
    }
}
```

Then install it via `composer install` or `composer update`.

> For Laravel users, please check the [Version Compatibility](2-Version-Compatibility.md) section.

## Laravel

### Setup

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [
    ...
    Arcanedev\NoCaptcha\NoCaptchaServiceProvider::class,
],
```

And the facade in the `aliases` array:

```php
'aliases' => [
    ...
    'Captcha' => Arcanedev\NoCaptcha\Facades\NoCaptcha::class,
],
```

> For Laravel 4.2 (PHP 5.4), the config file is located in `app/config/app.php`

In the `providers` array:

```php
'providers' => [
    ...
    'Arcanedev\NoCaptcha\Laravel\ServiceProvider',
],
```

And the facade in the `aliases` array:

```php
'aliases' => [
    ...
    'Captcha' => 'Arcanedev\NoCaptcha\Laravel\Facade',
],
```
