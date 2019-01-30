<?php

require_once(__DIR__.'/vendor/autoload.php');

use \Arcanedev\NoCaptcha\NoCaptchaV2;

$captcha = new NoCaptchaV2(
    'your-secret-key',
    'your-site-key'
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
    <?php
        echo $captcha->audio();
        // Or : $captcha->display('captcha', ['data-type' => 'audio']);
    ?>
    <button type="submit">Submit</button>
</form>

<?php echo $captcha->script(); ?>
