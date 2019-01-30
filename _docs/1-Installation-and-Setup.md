# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  4. [Extras](4-Extras.md)
  5. [FAQ](5-FAQ.md)

## Requirements

    - PHP >= 7.1.3
    - ext-curl: *
    - ext-json: *

To use reCAPTCHA, you need to have a `site key` and a `secret key`. [Click here](https://www.google.com/recaptcha/admin) to setup a domain and get your keys.

The `site key` is using for the widget and the `secret key` is used to validate the response we get from Google.

For more details, check the [official documentation](https://developers.google.com/recaptcha/).

## Version Compatibility

| noCaptcha                                                           | Laravel                                                                                                             |
|:--------------------------------------------------------------------|:--------------------------------------------------------------------------------------------------------------------|
| ![noCaptcha v1.x][no_captcha_1_x]                                   | ![Laravel v4.2][laravel_4_2]                                                                                        |
| ![noCaptcha v3.x][no_captcha_3_x]                                   | ![Laravel v5.0][laravel_5_0] ![Laravel v5.1][laravel_5_1] ![Laravel v5.2][laravel_5_2] ![Laravel v5.3][laravel_5_3] |
| ![noCaptcha v4.x][no_captcha_4_x]                                   | ![Laravel v5.4][laravel_5_4]                                                                                        |
| ![noCaptcha v5.x][no_captcha_5_x]                                   | ![Laravel v5.5][laravel_5_5]                                                                                        |
| ![noCaptcha v6.x][no_captcha_6_x]                                   | ![Laravel v5.6][laravel_5_6]                                                                                        |
| ![noCaptcha v7.x][no_captcha_7_x] ![noCaptcha v8.x][no_captcha_8_x] | ![Laravel v5.7][laravel_5_7]                                                                                        |

> **Note :** This is a framework-agnostic package, so you can use any version of this package in your PHP project.

[laravel_4_2]:    https://img.shields.io/badge/v4.2-supported-brightgreen.svg?style=flat-square "Laravel v4.2"
[laravel_5_0]:    https://img.shields.io/badge/v5.0-supported-brightgreen.svg?style=flat-square "Laravel v5.0"
[laravel_5_1]:    https://img.shields.io/badge/v5.1-supported-brightgreen.svg?style=flat-square "Laravel v5.1"
[laravel_5_2]:    https://img.shields.io/badge/v5.2-supported-brightgreen.svg?style=flat-square "Laravel v5.2"
[laravel_5_3]:    https://img.shields.io/badge/v5.3-supported-brightgreen.svg?style=flat-square "Laravel v5.3"
[laravel_5_4]:    https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"
[laravel_5_5]:    https://img.shields.io/badge/v5.5-supported-brightgreen.svg?style=flat-square "Laravel v5.5"
[laravel_5_6]:    https://img.shields.io/badge/v5.6-supported-brightgreen.svg?style=flat-square "Laravel v5.6"
[laravel_5_7]:    https://img.shields.io/badge/v5.7-supported-brightgreen.svg?style=flat-square "Laravel v5.7"

[no_captcha_1_x]: https://img.shields.io/badge/version-1.*-blue.svg?style=flat-square "noCaptcha v1.*"
[no_captcha_3_x]: https://img.shields.io/badge/version-3.*-blue.svg?style=flat-square "noCaptcha v3.*"
[no_captcha_4_x]: https://img.shields.io/badge/version-4.*-blue.svg?style=flat-square "noCaptcha v4.*"
[no_captcha_5_x]: https://img.shields.io/badge/version-5.*-blue.svg?style=flat-square "noCaptcha v5.*"
[no_captcha_6_x]: https://img.shields.io/badge/version-6.*-blue.svg?style=flat-square "noCaptcha v6.*"
[no_captcha_7_x]: https://img.shields.io/badge/version-7.*-blue.svg?style=flat-square "noCaptcha v7.*"
[no_captcha_8_x]: https://img.shields.io/badge/version-8.*-blue.svg?style=flat-square "noCaptcha v8.*"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command `composer require arcanedev/no-captcha`.

## Laravel

### Setup

> **NOTE :** The package will automatically register itself if you're using Laravel `>= v5.5`, so you can skip this section.

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [
    ...
    Arcanedev\NoCaptcha\NoCaptchaServiceProvider::class,
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
