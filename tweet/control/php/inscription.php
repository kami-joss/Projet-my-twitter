<?php
require "../../model/php/bdd.php";
$username = $_POST['username'];
$mail = $_POST['email'];
$pass = $_POST['pass'];
$birth = $_POST['birth'];
$hash = password_hash($pass,PASSWORD_DEFAULT);

$user = new TWEET_base();
$user->login_db();
$checkMail = $user->suscribe_check_mail($mail);
if(isset($checkMail)) {
    echo 'existant';
} else {
    $user->inscription($username,$mail,$hash,$birth);
}
