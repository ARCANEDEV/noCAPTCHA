<?php

require_once(__DIR__.'/vendor/autoload.php');

use Arcanedev\NoCaptcha\NoCaptchaV3;

$captcha = new NoCaptchaV3(
    'SECRET-KEY',
    'SITE-KEY'
);

if ($_POST) {
    $response = $captcha->verify($_POST['g-recaptcha-response'] ?? null);

    echo $response->isSuccess()
        ? 'Yay ! You are a human.'
        : 'No ! You are a robot.';

    exit();
}
?>

<form method="POST">
    <input type="email" name="email"><br>
    <button type="submit">Submit</button>

    <?php echo $captcha->input('g-recaptcha-response'); ?>
</form>

<?php echo $captcha->script(); ?>
<?php echo $captcha->getApiScript(); ?>

<script>
    grecaptcha.ready(function() {
        window.noCaptcha.render('login', function (token) {
            document.querySelector('#g-recaptcha-response').value = token;
        });
    });
</script>
