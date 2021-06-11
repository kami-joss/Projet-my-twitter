<?php

function tokened($id) {
    $pass1 = strrev($id);
    $tab = [];
    for ($i=0; $i < strlen($pass1) ; $i++) { 
        $char = substr($pass1,$i,1);
        $ord = ord($char);
        $mult = $char * $i *3 +22;
        $mult2 = $ord + $i * $i *2;
        for ($i=0; $i < 10 ; $i++) { 
            $rand = rand(110,117);
            $chr = chr($rand);
            $tabRand[] = $chr;
        } 
        $random = implode('',$tabRand);
        $tab[] = chr($ord+$i) . ($ord+($i+2)) . $random . $mult . $mult2 ;

    }
    $pass = implode('',$tab);
    return $pass;
}
// echo hasher(9) . PHP_EOL;
// echo rand(101,132);
// echo chr(rand(101,132));
