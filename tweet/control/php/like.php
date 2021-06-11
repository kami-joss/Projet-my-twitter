<?php 
    include("../../model/php/bdd.php");
    $bdd = new TWEET_base;
    $bdd->login_db();

    $id = $_POST['id'];
    $token = $_POST['token'];
    //$test = $bdd->add_like($token, $id);
    $test = $bdd->is_liked($token, $id);

    /**
     * SI id_tweet est dans les likes ALORS on le retire
     */