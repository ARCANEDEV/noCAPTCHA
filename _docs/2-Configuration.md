# 2. Configuration

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  4. [Extras](4-Extras.md)
  5. [FAQ](5-FAQ.md)

There is not really a need to publish the configuration file. Both the `secret` and `sitekey` should be set in your environment file so it won't be available in your versioning system.

## Option 1 - Environment Configuration:

See [Environment Configuration documentation](http://laravel.com/docs/5.0/configuration#environment-configuration).

````
// Edit your .env file by adding this two lines and fill it with your keys.

NOCAPTCHA_SECRET=your-secret-key
NOCAPTCHA_SITEKEY=your-site-key
````

> For Laravel 4.2: [Protecting Sensitive Configuration](http://laravel.com/docs/4.2/configuration#protecting-sensitive-configuration)

## Option 2 - Publish configuration file:

Run `php artisan vendor:publish  --provider="Arcanedev\NoCaptcha\NoCaptchaServiceProvider"` to publish the config file.

Edit the `secret` and `sitekey` values in `config/no-captcha.php` file:

> For Laravel 4.2, run `php artisan config:publish arcanedev/no-captcha` and the file is located in `app/config/packages/arcanedev/no-captcha/config.php`.
