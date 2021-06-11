<?php
require ("../../model/php/bdd.php");
$search = $_POST['search'];

$user = new TWEET_base;
$user->login_db();

if (isset($search)){
    $tabArobase = $user->search($search);
    if ($tabArobase !== null){
        echo json_encode($tabArobase);
    }
    echo null;
}