<?php
    include("query.php");
    if(!isLogged())
        die("403");
    if(getRole()>1){
        die("403");
    }
    @$id = $_POST['id'];
    deleteFed($id);
    echo 204;
 ?>
