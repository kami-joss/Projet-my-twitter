<?php
require '../../model/php/bdd.php';

$user = new TWEET_base();
$user->login_db();

// ajoute le message +image upload
if (isset($_POST['tweet'])) {
    $tweet = $_POST['tweet'];
    $token=$_POST['token'];
    $user->add_news_feed($token, $tweet);
    
}

if (isset($_FILES['file'])){
    $image = $_FILES['file'];
    $name = $image['name'];
    $location = "../../view/images/img_tweet/" . $name;
    move_uploaded_file($image['tmp_name'], $location);
    $user->add_tweet_img($location);
    echo "<img src='$location' height='100px' width='150px' class = 'img-thumbnail' />";
}


if (isset($_POST['tokenFirst'])){
    $token = $_POST['tokenFirst'];
    $id = $user->get_id_user($token);
    $tabContenu = $user->news_feeds($id);
    if ( $tabContenu == null) {
        echo 'null';
    }
    else {
        echo json_encode($tabContenu);
    }
}
// Liste de message dactualitéa