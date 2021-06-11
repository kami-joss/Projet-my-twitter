<?php    
    include("..\..\model\php\bdd.php");
    $bdd = new TWEET_base;
    $bdd->login_db();
    $id = $bdd->get_id_user($_POST['token']);


    if(!isset($_POST["action"])) {
        $abonnements = $bdd->get_abo($id);
        $abonnements_encode = json_encode($abonnements);
        echo $abonnements_encode;
    }

    if(isset($_POST['action']) && $_POST['action'] == "delete") {
        $bdd->delete_abo($id, $_POST['id_abo']);
        print_r($_POST);
    }