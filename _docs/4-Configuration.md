# 4. Configuration

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

Run `php artisan vendor:publish` to publish the config file.

Edit the `secret` and `sitekey` values in `config/no-captcha.php` file:

> For Laravel 4.2, run `php artisan config:publish arcanedev/no-captcha` and the file is located in `app/config/packages/arcanedev/no-captcha/config.php`.

```php
<?php

return [
    'secret'  => getenv('NOCAPTCHA_SECRET')  ?: 'no-captcha-secret',
    'sitekey' => getenv('NOCAPTCHA_SITEKEY') ?: 'no-captcha-sitekey',
    'lang'    => app()->getLocale(),

    // ...
];
```

###### To :

```php
<?php

return [
    'secret'  => 'your-secret-key',
    'sitekey' => 'your-site-key',
    'lang'    => 'en',              // Optional

    // ...
];
```

## Additional configs

You can customize your captchas by setting-up a global `attributes` in your config file.

```
<?php

return [
    // ...

    /* ------------------------------------------------------------------------------------------------
     |  Attributes
     | ------------------------------------------------------------------------------------------------
     */
    'attributes' => [
        'data-theme' => null, // 'light', 'dark'
        'data-type'  => null, // 'image', 'audio'
        'data-size'  => null, // 'normal', 'compact'
    ],
];
```
