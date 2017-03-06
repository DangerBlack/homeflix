<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	require_once("query.php");

    @$secret = $_GET['secret'];

    if(!checkFed($secret)){
        die("403");
    }

    @$hash = $_GET['hash'];

    @$idmovie = getMovie($hash)['id'];
    if($idmovie!=null){
	   echo json_encode(getPost($idmovie));
    }else{
        die("404");
    }
?>
