<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Arcanedev\NoCaptcha\NoCaptcha;

$secret  = 'your-secret-key';
$sitekey = 'your-site-key';
$lang    = 'fr';
$captcha = new NoCaptcha($secret, $sitekey, $lang);

if ( ! empty($_POST)) {
    $response = $_POST['g-recaptcha-response'];
    $result   = $captcha->verify($response);

    echo $result === true
        ? 'Yay ! Tu es un humain.'
        : 'Non ! Tu es un robot.';

    exit();
}
?>

<form method="POST">
    <?php echo $captcha->display(); ?>
    <button type="submit">Envoyer</button>
</form>

<?php echo $captcha->script(); ?>
