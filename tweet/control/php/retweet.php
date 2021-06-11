<?php
    include("..\..\model\php\bdd.php");
    $bdd = new TWEET_base;
    $bdd->login_db();

    $bdd->retweet($_POST['token'], $_POST['id_post']);