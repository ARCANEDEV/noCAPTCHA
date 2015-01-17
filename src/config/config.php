<?php

return [
    'secret'  => getenv('NOCAPTCHA_SECRET')  ?: 'no-captcha-secret',
    'sitekey' => getenv('NOCAPTCHA_SITEKEY') ?: 'no-captcha-sitekey',
    'lang'    => app()->getLocale(),
];
