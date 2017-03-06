<?php
    require_once("query.php");


    if(!isLogged()){
        die("403");
    }

    if(getRole()<=1){
        echo getFedSecret();
    }else{
        die("403");
    }
 ?>
