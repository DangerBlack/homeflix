<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    include("query.php");
    require_once("config.php");
    //if(!isLogged())
    //    die("Non siete loggato");
    //$id=getId();
    @$folder = $_GET['folder'];
    @$order = $_GET['order'];

    if($folder=="default"){
        $folder = $DEFAULT_FOLDER;
    }

    if($folder == "mylist"){
        echo json_encode(getMyList());
    }else{
        echo json_encode(getMovieList($folder,$order));
    }
 ?>
