<?php
    include("query.php");
    if(!isLogged())
        die("403");
    if(getRole()>1){
        die("403");
    }
    @$id = $_POST['id'];

    $user = getUser($id)[0];
    if($user['role']>getRole()){
        deleteUser($id);
        echo 204;
    }else{
        die("403");
    }
 ?>
