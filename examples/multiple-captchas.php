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

<form method="POST">
    <?php echo $captcha->display(['id' => 'captcha1']); ?>
    <button type="submit">Submit</button>
</form>

<form method="POST">
    <?php echo $captcha->display(['id' => 'captcha2']); ?>
    <button type="submit">Submit</button>
</form>

<?php echo $captcha->scriptWithCallback(['captcha1', 'captcha2']); ?>
