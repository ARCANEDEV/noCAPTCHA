# 3. Usage

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
    * [Hard Coded (Any PHP Project)](#hard-coded-any-php-project)
    * [Laravel](#laravel)
  4. [Extras](4-Extras.md)
  5. [FAQ](5-FAQ.md)

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

**Note:** The `NoCaptcha` constructor accepts four arguments:

| Argument    | Required | Description                                            |
|-------------|----------|--------------------------------------------------------|
| $secret     | Yes      | Your secret key.                                       |
| $siteKey    | Yes      | Your site key.                                         |
| $lang       | No       | You can specify your language.                         |
| $attributes | No       | You can specify a global attributes for your captchas. |

Check the [examples folder](https://github.com/ARCANEDEV/noCAPTCHA/tree/master/examples) for more usage details.

#### Invisible Captcha

The code below explains how to enable and customize the invisible reCAPTCHA on your webpage.

```php
require_once(__DIR__ . '/../vendor/autoload.php');

use Arcanedev\NoCaptcha\NoCaptcha;

$secret  = 'your-secret-key';
$sitekey = 'your-site-key';
$captcha = new NoCaptcha($secret, $sitekey);

if ( ! empty($_POST)) {
    $response = $_POST[NoCaptcha::CAPTCHA_NAME];
    $result   = $captcha->verify($response);

    echo $result === true ? 'Yay ! You are a human.' : 'No ! You are a robot.';

    exit();
}
?>

<form method="POST" id="demo-form">
    <?php echo $captcha->button('Send', ['data-badge' => 'inline']); ?>
</form>

<?php echo $captcha->script(); ?>

<script>
    function onSubmit(token) {
        document.getElementById("demo-form").submit();
    }
</script>
```

**NOTE :** You need to specify the invisible version in your captcha admin page. Check this page for more details: https://developers.google.com/recaptcha/docs/versions

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
use Arcanedev\NoCaptcha\Rules\CaptchaRule;

$inputs   = Input::all();
$rules    = [
    // Other validation rules...
    'g-recaptcha-response' => ['required', new CaptchaRule],
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

For the `required` rule, you can customize it by adding your messages to `custom` array in the `resources/lang/xx/validation.php`:

```php
'custom' => [
    'g-recaptcha-response' => [
        'required' => 'Your custom validation message for captchas.',
    ],
],
```

> For Laravel 4.2, the lang folder is located in `app/lang`

```php
use Arcanedev\NoCaptcha\Rules\CaptchaRule;

$validator = Validator::make(Input::all(), [
    // Other validation rules...
    'g-recaptcha-response' => ['required', new CaptchaRule],
]);

if ($validator->fails()) {
    $errors = $validator->messages();

    var_dump($errors->first('g-recaptcha-response'));

    // Redirect back or throw an error
}
```

> For more advanced usage, check the [official recaptcha documentation](https://developers.google.com/recaptcha/intro).
