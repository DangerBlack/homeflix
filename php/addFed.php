<?php
    include("query.php");
    if(!isLogged())
        die("Non siete loggato");

    @$url = $_GET['url'];
    @$secret = $_GET['$secret'];
    echo json_encode(addFed($url,$secret));

 ?>
