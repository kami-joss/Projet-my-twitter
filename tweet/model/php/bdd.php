<?php
require 'pass.php';

class TWEET_base {
     
    protected $bdd;

    function login_db () {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "tweet_academy";
        
        try {
            $bdd = new PDO("mysql:host=$servername; dbname=$dbname","$username", "$password");
            $this->bdd = $bdd;
        } catch (Exception $e) {
            echo "erreur: " . ($e)->getMessage();
            die();
        }
    }

    function last_id (){
        $query = $this->bdd->query("SELECT id_user FROM user ORDER BY id_user DESC LIMIT 1")->fetch();
        $newID = $query['id_user'] + 1;
        return $newID;
    }

    function inscription($username,$mail,$pass,$birth,$photo = ""){
        $query = "INSERT INTO user (username, arobase, date_naissance, email, mdp, token) VALUES ('$username', '@$username', '$birth', '$mail', '$pass', '" . tokened($this->last_id()) . "')" ;
        $this->bdd->exec($query);
    }

    function suscribe_check_mail($mail) {
        $query = $this->bdd->query("SELECT email FROM user WHERE email = '$mail'");
        $fetch = $query->fetch();
        return $fetch['email'];
    }

    function hash_pass ($mail) {
        $query = $this->bdd->query("SELECT mdp FROM user WHERE email = '$mail'");
        $fetch = $query->fetch();
        return $fetch['mdp'];
    }
    
    function check_mail($mail,$pass) {
        $query = $this->bdd->query("SELECT email FROM user WHERE email = '$mail' AND mdp = '$pass'");
        $fetch = $query->fetch();
        return $fetch['email'];
    }
    function check_pass($mail,$pass) {
        $query = $this->bdd->query("SELECT mdp FROM user WHERE email = '$mail' AND mdp = '$pass'");
        $fetch = $query->fetch();
        return $fetch['mdp'];
    }

    function get_token($mail) {
        $query = $this->bdd->query("SELECT token FROM user WHERE email = '$mail'");
        $fetch = $query->fetch();
        return $fetch['token'];
    }

    function get_username($token) {
        $query = $this->bdd->query("SELECT username FROM user WHERE token = '$token'");
        $fetch = $query->fetch();
        return $fetch['username'];
    }

    function nb_abonnement($id_user) {
        $query = $this->bdd->query("SELECT COUNT(id_user) AS 'NB_ABO' FROM follow WHERE id_user = $id_user");
        $fetch = $query->fetch();
        return $fetch['NB_ABO'];
    }
    
    function get_arobase($token) {
        $query = $this->bdd->query("SELECT arobase FROM user WHERE token = '$token'");
        $fetch = $query->fetch();
        return $fetch['arobase'];
    }

    function get_id_user ($token) {
        $query = $this->bdd->query("SELECT id_user FROM user WHERE token = '$token'");
        $fetch = $query->fetch();
        return $fetch['id_user'];
    }

    function add_news_feed($token, $tweet=null, $tweet_img=null) {
        $id_user = $this->get_id_user($token);
        $this->bdd->exec("INSERT INTO tweet(id_user, contenu, date_tweet) VALUES ('$id_user', '$tweet', NOW())");
    }

    function get_id_followers($id) {
        $query = $this->bdd->query("SELECT id_user FROM follow WHERE id_follower = $id");
        $fetch = $query->fetchAll();
        $tabID = [];
        foreach ($fetch as $key => $value) {
            $tabID[] = $value['id_user'];
        }
        if ($tabID == ''){
            $IN = null;
        }
        else {
            $IN = implode(',',$tabID);
        }
        return $IN ;
    }

    function news_feeds($id){
        $followers = $this->get_id_followers($id);
        if ($followers == null) {
            $followers = 0;
        }
        $query = $this->bdd->query("SELECT  arobase, contenu, date_tweet, tweet.id_post, url_image  FROM tweet INNER JOIN user USING (id_user) LEFT JOIN images ON images.id_post = tweet.id_post WHERE id_user IN ($followers) ORDER BY id_post ASC" );
        if ($query !== false || $query !== '') {
            $tab = $query->fetchAll();
            $tabContenu = [];
            foreach ($tab as $key => $value) {
                $tabContenu[] = array('arobase' => $value['arobase'], 'contenu' => $value['contenu'], 'date_tweet' => $value['date_tweet'], 'id_post' => $value['id_post'], 'images' => $value['url_image']);
            } 
            return $tabContenu;
        }
        return $query;
    }
    
    function search($tap) {
        $query = $this->bdd->query("SELECT arobase, id_user FROM user WHERE username LIKE '%$tap%'");
        if ($query !== false || $query !== '') {
            $tabSearch = $query->fetchAll();
            foreach ($tabSearch as $key => $value) {
                $tabArobase [] = ['arobase' => $value['arobase'], 'id_user' => $value['id_user']];
            }
            return $tabArobase;
        }
        return null;
    }

    function profil_data($id){
        $query = $this->bdd->query("SELECT username, arobase, COUNT(follow.id_user) AS 'nb_abonnees', (SELECT COUNT(id_follower) FROM follow WHERE id_follower= $id) AS 'nb_abonnements', bio, location, email FROM user LEFT JOIN follow USING (id_user) WHERE user.id_user = $id;");
        $fetchAll = $query->fetchAll();

        foreach ($fetchAll as $key => $value) {
            $tab_Data_profil[] =  ['username' => $value['username'], 'arobase' => $value['arobase'], 'nb_abonnes' => $value['nb_abonnees'], 'nb_abonnements' => $value['nb_abonnements'], 'bio' => $value['bio'], 'location' => $value['location'], 'email' => $value['email']];
        }
        return $tab_Data_profil[0];
    }

    function profil_tweets($id) {
        $query = $this->bdd->query("SELECT arobase, contenu, id_post, date_tweet, id_image, url_image FROM tweet LEFT JOIN user USING (id_user) LEFT JOIN images USING (id_post) WHERE id_user=$id OR id_user IN ((SELECT id_follower FROM follow WHERE id_user= $id));");
        $fetchAll =$query->fetchAll();

        foreach ($fetchAll as  $value) {
            $tab_tweets[] = ['arobase' => $value['arobase'], 'contenu' => $value['contenu'], 'id_post'=>$value['id_post'], 'date_tweet' => $value['date_tweet'], 'id_image' => $value['id_image'], 'url_image' => $value['url_image']]; 
        }
        return $tab_tweets;
    }

    function check_follow($token, $id) {
        $id_user = $this->get_id_user($token);
        $query = $this->bdd->query("SELECT id_user FROM follow WHERE id_user =$id_user AND id_follower = $id");
        $fetch = $query->fetch();
        return $fetch['id_user'];
    }

    function unfollow($token, $id){
        $id_user = $this->get_id_user($token);
        $this->bdd->exec("DELETE FROM follow WHERE id_user = $id_user AND id_follower =$id");

    }

    function follow_one ($token,$follow){
        $id_user = $this->get_id_user($token);
        $query = "INSERT INTO follow VALUES ($id_user, $follow)";
        $this->bdd->exec($query);
    }

    function get_id_post_for_img(){
        $query = $this->bdd->query("SELECT id_post FROM tweet ORDER BY id_post DESC LIMIT 1");
        $fetch = $query->fetch();
        return $fetch['id_post'];
    }

    function add_tweet_img ($path){
        $current_id_post = ($this->get_id_post_for_img())+1;
        $this->bdd->exec("INSERT INTO images VALUES (0, $current_id_post, '$path') ");
    }

    function add_photo_profil ($path, $token){
        $id_user = $this->get_id_user($token);
        $this->bdd->exec("INSERT INTO photo_de_profil VALUES (0, '$path', $id_user) ");
    }

    // --------------------------------DAVID-------------------------------

    function list_arobase_mot($mot){        
        $list_deroulant=[];
        foreach($this->bdd->query("SELECT id_user,arobase  FROM user WHERE arobase LIKE '%{$mot}%' " ) as $key => $value){
            $list_deroulant[]=[$value['id_user'],$value['arobase']];
           
        };
         return $list_deroulant;
        
    }

    function list_message_limit_20($id_connecte, $cible , $token){
        $resultat_message=[];
        foreach($this->bdd->query("SELECT  date_msg, id_expediteur, contenu  FROM message_prive WHERE id_expediteur = $id_connecte AND id_destinataire = $cible  OR (id_expediteur = $cible  AND id_destinataire = $id_connecte) ORDER BY id_msg DESC LIMIT 20" ) as $key => $value){
            $arobase=$this->get_arobase($token);
            $resultat_message[]=[$value['date_msg'],$arobase,$value['contenu']];
        };

        return $resultat_message;
    }

    function add_message($message,$id_connecte,$cible){
        $query = $this->bdd->query(" INSERT INTO message_prive(contenu,id_expediteur,id_destinataire,date_msg)  VALUES ( '$message','$id_connecte','$cible', NOW()   ) " );
        // $refresh=$this->list_message_limit_20($id_connecte,$cible);
        // return $refresh;
        
    }

    function chargement_des_contacts($id_connecte){        
        $resultat_message=[];
        $id_connecte_string = intval($id_connecte); // transforme le int en str
        foreach($this->bdd->query("SELECT  id_expediteur,id_destinataire  FROM message_prive WHERE id_expediteur='{$id_connecte}' OR id_destinataire='{$id_connecte}'  ORDER BY id_msg  DESC LIMIT 20  " ) as $key => $value){
            
            if($value['id_expediteur']==$id_connecte_string  ){
                $query=($this->bdd->query("SELECT id_user,arobase  FROM user WHERE id_user='{$value['id_destinataire']}' "));
                $fetch = $query->fetch();
                if ((in_array([$fetch['id_user'],$fetch['arobase']], $resultat_message))!=true) {
                    $resultat_message[]=[$fetch['id_user'],$fetch['arobase']];
                }
                
            }else if($value['id_destinataire']==$id_connecte_string  ){
                $query=($this->bdd->query("SELECT id_user,arobase  FROM user WHERE id_user='{$value['id_expediteur']}' "));
                $fetch = $query->fetch();
                if ((in_array([$fetch['id_user'],$fetch['arobase']], $resultat_message))!=true) {
                    $resultat_message[]=[$fetch['id_user'],$fetch['arobase']];
                    
                }
                
            }
        }     
        return ($resultat_message);
    }
        // -----------------------------------JOSS---------------------------

        
    function set_profil ($token, $nom, $bio, $date_naissance, $email) {
        echo $token, $nom, $bio, $date_naissance, $email;
        $this->bdd->exec("UPDATE user SET username = '$nom', date_naissance = '$date_naissance', email = '$email', bio = '$bio' WHERE token = '$token'");
    }

    function set_password ($token, $password) {
        $id = $this->get_id_user($token);
        $password_h = password_hash($password, PASSWORD_DEFAULT);
        $this->bdd->exec("UPDATE user SET mdp = '$password_h' WHERE token = '$token'");
    }

    function get_abo ($id) {
        $query = $this->bdd->query("SELECT user.id_user, username, arobase FROM follow LEFT JOIN user ON follow.id_follower = user.id_user WHERE follow.id_user = '$id'");
        $abonnement = $query->fetchAll();
        return $abonnement;
    }

    function delete_abo ($id_user, $id_abo) {
        $this->bdd->exec("DELETE FROM follow WHERE id_user = '$id_user' AND id_follower = '$id_abo'");
    }

    function add_follow ($id_user, $id_abo) {
        $this->bdd->exec("INSERT INTO follow VALUES ($id_user, $id_abo)");
    }
    
    function add_like($token, $id_post) {
        try {
            $id_user = $this->get_id_user($token);
            $this->bdd->exec("INSERT INTO likes VALUES (0, $id_post, $id_user)");
            return true;
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function remove_like($token, $id_post) {
        try {
            $id_user = $this->get_id_user($token);
            $this->bdd->exec("DELETE FROM likes WHERE id_post = $id_post AND id_user = $id_user");
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function is_liked($token, $id_post) {
        $id_user = $this->get_id_user($token);
        $query = $this->bdd->query("SELECT id_like FROM likes WHERE id_post = $id_post AND id_user = $id_user");
        $result = $query->fetch();
        if (!empty($result)) {
            $this->remove_like($token, $id_post);
        } else {
            $this->add_like($token, $id_post);
        }
    }

    function reply($token, $id_post, $reply) {
        $id_user = $this->get_id_user($token);
        $query = $this->bdd->exec("INSERT INTO reply VALUES (0, $id_user, $id_post, '$reply', NOW())");

    }

    function retweet($token, $id_post) {
        $id_user = $this->get_id_user($token);
        $this->bdd->exec("INSERT INTO retweet VALUES ($id_user, $id_post, NOW(), 0)");
    }
    
}