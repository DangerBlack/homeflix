<?php
    include("query.php");
    if(!isLogged())
        die("Non siete loggato");

    @$name = $_POST['name'];
    @$url = $_POST['url'];
    @$secret = $_POST['secret'];
    addFed($name,$url,$secret);
    echo 201;
 ?>
