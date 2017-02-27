<?php
    include("query.php");
    if(!isLogged())
        die("Non siete loggato");

    @$idmovie = $_GET['id'];
    echo json_encode(deleteFavorite($idmovie));

 ?>
