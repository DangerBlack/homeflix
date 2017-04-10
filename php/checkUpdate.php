<?php
    require_once("query.php");


    if(!isLogged()){
        die("403");
    }

    if(getRole()<=1){
        @$remoteV = file_get_contents('https://raw.githubusercontent.com/DangerBlack/homeflix/master/installer/version.txt');
        $remoteV = floatval($remoteV);
        echo "remoteV".$remoteV."-";
        $v = getVersion();
        $v = floatval($v);
        echo "localV".$v."-";
        if($remoteV>$v){
            echo $remoteV;
        }else{
            echo -1;
        }
    }else{
        die("403");
    }
 ?>
