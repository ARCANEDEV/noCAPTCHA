# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  4. [Extras](4-Extras.md)
  5. [FAQ](5-FAQ.md)

## Requirements

    - PHP >= 5.6
    - ext-curl: *
    - ext-json: *

To use reCAPTCHA, you need to have a `site key` and a `secret key`. [Click here](https://www.google.com/recaptcha/admin) to setup a domain and get your keys.

The `site key` is using for the widget and the `secret key` is used to validate the response we get from Google.

For more details, check the [official documentation](https://developers.google.com/recaptcha/).

## Version Compatibility

| noCaptcha                         | Laravel                                                                                                             |
|:----------------------------------|:--------------------------------------------------------------------------------------------------------------------|
| ![noCaptcha v1.x][no_captcha_1_x] | ![Laravel v4.2][laravel_4_2]                                                                                        |
| ![noCaptcha v3.x][no_captcha_3_x] | ![Laravel v5.0][laravel_5_0] ![Laravel v5.1][laravel_5_1] ![Laravel v5.2][laravel_5_2] ![Laravel v5.3][laravel_5_3] |
| ![noCaptcha v4.x][no_captcha_4_x] | ![Laravel v5.4][laravel_5_4]                                                                                        |

> **Note :** This is a framework-agnostic package, so you can use any version of this package in your PHP project.

[laravel_4_2]:    https://img.shields.io/badge/v4.2-supported-brightgreen.svg?style=flat-square "Laravel v4.2"
[laravel_5_0]:    https://img.shields.io/badge/v5.0-supported-brightgreen.svg?style=flat-square "Laravel v5.0"
[laravel_5_1]:    https://img.shields.io/badge/v5.1-supported-brightgreen.svg?style=flat-square "Laravel v5.1"
[laravel_5_2]:    https://img.shields.io/badge/v5.2-supported-brightgreen.svg?style=flat-square "Laravel v5.2"
[laravel_5_3]:    https://img.shields.io/badge/v5.3-supported-brightgreen.svg?style=flat-square "Laravel v5.3"
[laravel_5_4]:    https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"

[no_captcha_1_x]: https://img.shields.io/badge/version-1.*-blue.svg?style=flat-square "noCaptcha v1.*"
[no_captcha_3_x]: https://img.shields.io/badge/version-3.*-blue.svg?style=flat-square "noCaptcha v3.*"
[no_captcha_4_x]: https://img.shields.io/badge/version-4.*-blue.svg?style=flat-square "noCaptcha v4.*"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command `composer require arcanedev/no-captcha`.

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
