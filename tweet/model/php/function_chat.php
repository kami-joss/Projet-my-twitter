<?php
function print_message($affiche){
    $somme_message="";
    $people1="";
    $people2="";
    $counter=2;
    foreach($affiche as $value){
        if( ($people1=="" AND $people2=="") || ($people1==$value[1] and $value[1]!=$people2)){
            $people1=$value[1];
            $row_message="<div class='contacte text-dark ' >"."<div class='alert alert-primary' role='alert'>".
            $value[0]."</br>".$value[1]." : ".$value[2]."</div></div>";
        }else{
            $people2=$value[1];
            $row_message="<div class='contacte text-dark text-right ' >"."<div class='alert alert-warning' role='alert'>".
            $value[0]."</br>"."@".$value[1]." : ".$value[2]."</div></div>";
        }
        $counter++;
        $somme_message.=$row_message;
    }
    echo ($somme_message);
}
?>