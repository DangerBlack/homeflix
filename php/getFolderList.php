<?php
    require_once("query.php");
    require_once("config.php");


    if(isLogged()){
        echo json_encode($FOLDER_PATH);
    }

 ?>
