# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  4. [Extras](4-Extras.md)
  5. [FAQ](5-FAQ.md)

## Requirements

To use reCAPTCHA, you need to have a `site key` and a `secret key`. [Click here](https://www.google.com/recaptcha/admin) to setup a domain and get your keys.

The `site key` is using for the widget and the `secret key` is used to validate the response we get from Google.

For more details, check the [official documentation](https://developers.google.com/recaptcha/).

## Version Compatibility

| Laravel                      | noCaptcha                                                           |
|:-----------------------------|:--------------------------------------------------------------------|
| ![Laravel v9.x][laravel_9_x] | ![noCaptcha v13.x][no_captcha_13_x]                                 |
| ![Laravel v8.x][laravel_8_x] | ![noCaptcha v12.x][no_captcha_12_x]                                 |
| ![Laravel v7.x][laravel_7_x] | ![noCaptcha v11.x][no_captcha_11_x]                                 |
| ![Laravel v6.x][laravel_6_x] | ![noCaptcha v10.x][no_captcha_10_x]                                 |
| ![Laravel v5.8][laravel_5_8] | ![noCaptcha v9.x][no_captcha_9_x]                                   |
| ![Laravel v5.7][laravel_5_7] | ![noCaptcha v7.x][no_captcha_7_x] ![noCaptcha v8.x][no_captcha_8_x] |
| ![Laravel v5.6][laravel_5_6] | ![noCaptcha v6.x][no_captcha_6_x]                                   |
| ![Laravel v5.5][laravel_5_5] | ![noCaptcha v5.x][no_captcha_5_x]                                   |
| ![Laravel v5.4][laravel_5_4] | ![noCaptcha v4.x][no_captcha_4_x]                                   |
| ![Laravel v5.3][laravel_5_3] | ![noCaptcha v3.x][no_captcha_3_x]                                   |
| ![Laravel v5.2][laravel_5_2] | ![noCaptcha v3.x][no_captcha_3_x]                                   |
| ![Laravel v5.1][laravel_5_1] | ![noCaptcha v3.x][no_captcha_3_x]                                   |
| ![Laravel v5.0][laravel_5_0] | ![noCaptcha v3.x][no_captcha_3_x]                                   |
| ![Laravel v4.2][laravel_4_2] | ![noCaptcha v1.x][no_captcha_1_x]                                   |

> **Note :** This is a framework-agnostic package, so you can use any version of this package in your PHP project.

[laravel_9_x]: https://img.shields.io/badge/version-9.x-blue.svg?style=flat-square "Laravel v9.x"
[laravel_8_x]: https://img.shields.io/badge/version-8.x-blue.svg?style=flat-square "Laravel v8.x"
[laravel_7_x]: https://img.shields.io/badge/version-7.x-blue.svg?style=flat-square "Laravel v7.x"
[laravel_6_x]: https://img.shields.io/badge/version-6.x-blue.svg?style=flat-square "Laravel v6.x"
[laravel_5_8]: https://img.shields.io/badge/version-5.8-blue.svg?style=flat-square "Laravel v5.8"
[laravel_5_7]: https://img.shields.io/badge/version-5.7-blue.svg?style=flat-square "Laravel v5.7"
[laravel_5_6]: https://img.shields.io/badge/version-5.6-blue.svg?style=flat-square "Laravel v5.6"
[laravel_5_5]: https://img.shields.io/badge/version-5.5-blue.svg?style=flat-square "Laravel v5.5"
[laravel_5_4]: https://img.shields.io/badge/version-5.4-blue.svg?style=flat-square "Laravel v5.4"
[laravel_5_3]: https://img.shields.io/badge/version-5.3-blue.svg?style=flat-square "Laravel v5.3"
[laravel_5_2]: https://img.shields.io/badge/version-5.2-blue.svg?style=flat-square "Laravel v5.2"
[laravel_5_1]: https://img.shields.io/badge/version-5.1-blue.svg?style=flat-square "Laravel v5.1"
[laravel_5_0]: https://img.shields.io/badge/version-5.0-blue.svg?style=flat-square "Laravel v5.0"
[laravel_4_2]: https://img.shields.io/badge/version-4.2-blue.svg?style=flat-square "Laravel v4.2"

[no_captcha_13_x]: https://img.shields.io/badge/version-13.x-blue.svg?style=flat-square "noCaptcha v13.x"
[no_captcha_12_x]: https://img.shields.io/badge/version-12.x-blue.svg?style=flat-square "noCaptcha v12.x"
[no_captcha_11_x]: https://img.shields.io/badge/version-11.x-blue.svg?style=flat-square "noCaptcha v11.x"
[no_captcha_10_x]: https://img.shields.io/badge/version-10.x-blue.svg?style=flat-square "noCaptcha v10.x"
[no_captcha_9_x]:  https://img.shields.io/badge/version-9.x-blue.svg?style=flat-square "noCaptcha v9.x"
[no_captcha_8_x]:  https://img.shields.io/badge/version-8.x-blue.svg?style=flat-square "noCaptcha v8.x"
[no_captcha_7_x]:  https://img.shields.io/badge/version-7.x-blue.svg?style=flat-square "noCaptcha v7.x"
[no_captcha_6_x]:  https://img.shields.io/badge/version-6.x-blue.svg?style=flat-square "noCaptcha v6.x"
[no_captcha_5_x]:  https://img.shields.io/badge/version-5.x-blue.svg?style=flat-square "noCaptcha v5.x"
[no_captcha_4_x]:  https://img.shields.io/badge/version-4.x-blue.svg?style=flat-square "noCaptcha v4.x"
[no_captcha_3_x]:  https://img.shields.io/badge/version-3.x-blue.svg?style=flat-square "noCaptcha v3.x"
[no_captcha_1_x]:  https://img.shields.io/badge/version-1.x-blue.svg?style=flat-square "noCaptcha v1.x"

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
