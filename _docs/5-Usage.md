# 5. Usage

## Table of contents

* [Hard Coded (Any PHP Project)](#hard-coded-any-php-project)
* [Laravel](#laravel)

### Hard Coded (Any PHP Project)

Checkout example below:

```php
<?php

require_once('vendor/autoload.php');

use Arcanedev\NoCaptcha\NoCaptcha;

$secret  = 'your-secret-key';
$sitekey = 'your-site-key';
$captcha = new NoCaptcha($secret, $sitekey);

if ( ! empty($_POST)) {
    // You need to check also if the $_POST['g-recaptcha-response'] is not empty.
    $response = $_POST['g-recaptcha-response'];
    $result   = $captcha->verify($response);

    echo $result === true
        ? 'Yay ! You are a human.'
        : 'No ! You are a robot.';

    exit();
}
?>

<form action="?" method="POST">
    <?php echo $captcha->display(); ?>
    <button type="submit">Submit</button>
</form>

<?php
// At the bottom, before the </body> (If you're a good programmer and you listen to your mother)
echo $captcha->script();
?>
```

Check the [examples folder](https://github.com/ARCANEDEV/noCAPTCHA/tree/master/examples) for more usage details.

### Laravel

#### Views

Insert reCAPTCHA inside your form using one of this examples:

###### By using Blade syntax

```php
{!! Form::open([...]) !!}
    // Other inputs...
    {!! Form::captcha() !!}  OR  {!! Captcha::display() !!}
    {!! Form::submit('Submit') !!}
{!! Form::close() !!}

// Remember what your mother told you
{!! Captcha::script() !!}
```

> For Laravel 4.2, use `{{ ... }}` instead of `{!! ... !!}`

###### Without using Blade syntax

```php
<?php

echo Form::open([...]);
    // Other inputs...
    echo Form::captcha();
    echo Form::submit('Submit');
echo Form::close();

?>

<?php echo Captcha::script(); ?>
```

#### Back-end (Controller or somewhere in your project ...)

To validate the response we get from Google, your can use the `captcha` rule in your validator:

```php
$inputs   = Input::all();
$rules    = [
    // Other validation rules...
    'g-recaptcha-response' => 'required|captcha',
];
$messages = [
    'g-recaptcha-response.required' => 'Your custom validation message.',
    'g-recaptcha-response.captcha'  => 'Your custom validation message.',
];

$validator = Validator::make($inputs, $rules, $messages);

if ($validator->fails()) {
    $errors = $validator->messages();

    var_dump($errors->first('g-recaptcha-response'));

    // Redirect back or throw an error
}
```

If you want to manage the localized messages, edit the `validation.php` files inside your lang directory.

For example:
```php
// resources/lang/en/validation.php
return [
    ...
    // Add this line with your custom message
    'captcha'   => "If you read this message, then you're a robot.",
];
```
```php
// resources/lang/fr/validation.php
return [
    ...
    // Ajoutez cette ligne avec votre message personnalisé
    'captcha'   => 'Si vous lisez ce message, alors vous êtes un robot.',
];
```

> For Laravel 4.2, the lang folder is located in `app/lang`

```php
$validator = Validator::make(Input::all(), [
    // Other validation rules...
    'g-recaptcha-response' => 'required|captcha',
]);

if ($validator->fails()) {
    $errors = $validator->messages();

    var_dump($errors->first('g-recaptcha-response'));

    // Redirect back or throw an error
}
```
