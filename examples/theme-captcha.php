<?php

require_once "../vendor/autoload.php";

use Arcanedev\NoCaptcha\NoCaptcha;

$secret  = 'your-secret-key';
$sitekey = 'your-site-key';
$captcha = new NoCaptcha($secret, $sitekey);

if ( ! empty($_POST)) {
    $response = $_POST['g-recaptcha-response'];
    $result   = $captcha->verify($response);

    echo $result === true ? 'Yay ! You are a human.' : 'No ! You are a robot.';

    exit();
}
?>

<form action="?" method="POST">
    <?php
        echo $captcha->image(['data-theme' => 'dark']);
        // Or : $captcha->display(['data-theme' => 'light']);
    ?>
    <button type="submit">Submit</button>
</form>

<?php echo $captcha->script(); ?>
