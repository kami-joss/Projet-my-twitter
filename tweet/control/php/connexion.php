<?php
require '../../model/php/bdd.php';
$mail = $_POST['mail'];
$pass = $_POST['pass'];

$user = new TWEET_base();
$user->login_db();
$hash_pass = $user->hash_pass($mail);
$verify = password_verify($pass,$hash_pass);
if ($verify) {
    $checkPass = $user->check_pass($mail,$hash_pass);
    $checkMail = $user->check_mail($mail,$hash_pass);
    if (isset($checkMail) && isset($checkPass))   {
        echo $user->get_token($mail);
    } else {
        echo "fail";
    }
} else {
    echo 'fail';
}
