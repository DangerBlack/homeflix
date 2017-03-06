<?php
    require_once("query.php");
    require_once("config.php");


    if(!isLogged()){
        die("403");
    }

    if(getRole()<=1){
        echo json_encode(getFed());
    }else{
        die("403");
    }
 ?>
