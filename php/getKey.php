<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include("query.php");
    //if(!isLogged())
    //    die("Non siete loggato");
    //$id=getId();
    echo getSecretKey();

 ?>
