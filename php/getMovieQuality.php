<?php
    include("query.php");
    include("qualityExtractor.php");

    if(!isLogged())
        die("Non siete loggato");

    $hash = $_GET['movie'];
    $movie = getMovie($hash);
    //echo $movie['url'];
    $info = checkQuality($movie['url']);

    echo json_encode($info);
 ?>
