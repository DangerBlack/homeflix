<?php
    include("query.php");
    if(!isLogged())
        die("Non siete loggato");

    @$idmovie = $_POST['id'];
    @$mex = $_POST['mex'];
    @$star = $_POST['star'];
    addPost($idmovie,$mex,$star);
    echo "201";

 ?>
