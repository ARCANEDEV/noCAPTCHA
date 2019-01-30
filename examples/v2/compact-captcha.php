<?php
//
require_once "../vendor/autoload.php";

use Arcanedev\NoCaptcha\NoCaptchaV2;

$captcha = new NoCaptchaV2('your-secret-key', 'your-site-key');

if ($_POST) {
    $response = $captcha->verify($_POST['g-recaptcha-response'] ?? null);

    echo $response->isSuccess()
        ? 'Yay ! You are a human.'
        : 'No ! You are a robot.';

    exit();
}

?>

<form method="POST">
    <?php echo $captcha->display('captcha', ['data-size' => 'compact']); ?>

    <button type="submit">Submit</button>
</form>

<?php echo $captcha->script(); ?>
