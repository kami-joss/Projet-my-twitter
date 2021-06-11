<?php
require "../../model/php/bdd.php";
$user = new TWEET_base;
$user->login_db(); 

if (isset($_POST['id'])){
    $id = $_POST['id'];
    $token = $_POST['token'];
    $checkUser = $user->get_id_user($token);
    $tab_data_prfil = $user->profil_data($id);
    if ($id == $checkUser) {
        $tab_data_prfil[] = "removeButton"; 
    }
    echo json_encode($tab_data_prfil);
}

if (isset($_POST['id_tweet'])){
    $id_profil = $_POST['id_tweet'];
    $tab_tweets = $user->profil_tweets($id_profil);
    echo json_encode($tab_tweets);
}

if(isset($_POST['id_check_follow']) && isset($_POST['token_check_follow']) ){
    $token_follow = $_POST['token_check_follow'];
    $id_check = $_POST['id_check_follow'];
    $check_follow = $user->check_follow($token_follow, $id_check);
    if(!empty($check_follow)){  
        echo "abonne";      // si echo alors déjà abonné
    }
}

if(isset($_POST['id_to_follow']) && isset($_POST['token_follow'])) {
    $id_to_follow = $_POST['id_to_follow'];
    $token_follow = $_POST['token_follow'];
    $check_follow = $user->check_follow($token_follow, $id_to_follow);
    if(empty($check_follow)){ 
        $user->follow_one($token_follow, $id_to_follow);
        echo "I follow";
    }
}

if (isset($_POST['id_del'])){
    $id_follow = $_POST['id_del'];
    $del_token = $_POST['del_follow'];
    $user->unfollow($del_token, $id_follow);
    echo 'supp';
}

if (isset($_POST['user_self'])){
    $token = $_POST['user_self'];
    $id = $user->get_id_user($token);
    echo $id;
}
