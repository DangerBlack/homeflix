<?php
    include("query.php");

    if(!isLogged())
        die("Non siete loggati");

    $id = $_POST['id'];
    $hash = getMovieHashFromId($id);
    $title = $_POST['title'];

    $confirmed = -1;
    if(getRole()<=1){
        $confirmed = -2;
    }
    updateMovieTitle($hash,$title,$confirmed);
    echo "201";
 ?>
