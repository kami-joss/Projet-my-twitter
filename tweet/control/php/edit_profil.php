<?php   
    include("..\..\model\php\bdd.php");
    $bdd = new TWEET_base;
    $bdd->login_db();

    // echo $_POST;
    // copy($_POST['photo'], '..\..\view\ressources\test.jpg');

    if (!isset($_POST['password']) && !isset($_POST['nom'])) {
        $id = $bdd->get_id_user($_POST['token']);
        $infos_perso = $bdd->profil_data($id);
        $infos_perso_encode = json_encode($infos_perso);
        echo $infos_perso_encode;
    }

    if (isset($_POST['nom'])) {
        $bdd->set_profil($_POST['token'], $_POST['nom'], $_POST['bio'], $_POST['date_naissance'], $_POST['email']);
    }

    if (isset($_POST['password']) && isset($_POST['password_confirm'])) {
        if ($_POST['password'] !== $_POST['password_confirm']) {
            echo "false";
        }
        else {
            $bdd->set_password($_POST['token'], $_POST['password']);
            echo "true";
        }
    }

    if (isset($_FILES['file'])){
        $token = $_POST['token'];
        $image = $_FILES['file'];
        $name = $image['name'];
        $location = "../../view/images/photo_profil/" . $name;
        move_uploaded_file($image['tmp_name'], $location);
        $user->add_photo_profil($location, $token);
        echo "<img src='$location' height='100px' width='150px' class = 'img-thumbnail' />";
    }