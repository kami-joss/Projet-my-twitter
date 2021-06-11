<?php
require '../../model/php/bdd.php';
include '../../model/php/function_chat.php';
// $people=$_POST['people'];
// 
// $filename = $_FILES['file']['name']; 
$user = new TWEET_base();
$user->login_db();
//$user_connecte=2; // a supprimer lorsque tout marche connexion auto sur id user 2 donc saidolim

if(isset($_POST['chargementContacte']) and !empty($_POST['chargementContacte'])){
    $chargementcontacte=$_POST['chargementContacte'];
    $token=$_POST['token'];
    $user_connecte=$user->get_id_user($token);

    $les_derniers_contacts = $user->chargement_des_contacts($user_connecte);
    $contact_auto = '';
    foreach($les_derniers_contacts as $key =>$value){
        $list="<div class='contacte text-primary' data-action='".$value[0]."'>@".$value[1]."</div>";
        $contact_auto .= $list;
    }
    // echo $token;
    echo "<div id='auto_contacte'>".$contact_auto."</div>";
    
}

if(isset($_POST['mot']) and !empty($_POST['mot'])){
    $mot=$_POST['mot'];
    $menu_deroulant="";
    $list_candidat=$user->list_arobase_mot($mot);
    foreach($list_candidat as $key =>$value){
        $list="<div class='contacte text-primary' data-action='".$value[0]."'  >@".$value[1]."</div>";
        $menu_deroulant.=$list;
        
    }
    echo($menu_deroulant);
}

if(isset($_POST['id_user']) and !empty($_POST['id_user']) and $_POST['list_message_on'] != 0){
    $cible = $_POST['id_user'];
    $token=$_POST['token'];

    $user_connecte = $user->get_id_user($token);
    $historique = $user->list_message_limit_20($user_connecte,$cible, $token);
    print_message($historique);
    
}

if( (isset($_POST['message']) and !empty($_POST['message'])) and  (isset($_POST['id_user']) and !empty($_POST['id_user']))){
    $id_user = $_POST['id_user'];
    $list_message_on = $_POST['list_message_on'];
    $id_user=$_POST['id_user'];
    $message=$_POST['message'];
    $token=$_POST['token'];

    $user_connecte = $user->get_id_user($token);
    $contact_auto = '';
    $add_message=$user->add_message($message,$user_connecte,$id_user); 
    // $list_message=print_message($add_message); 
    $les_derniers_contacts = $user->chargement_des_contacts($user_connecte);
    foreach($les_derniers_contacts as $key =>$value){
        $list="<div class='contacte text-primary' data-action='".$value[0]."'>".$value[1]."</div>";
        $contact_auto.=$list;
    }
    $recharge_last_contact="<div id='auto_contacte'>".$contact_auto."</div>";
    echo "@@@@@".$recharge_last_contact;
}

?>