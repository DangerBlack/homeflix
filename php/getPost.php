<?php
	include("query.php");
	if(!isLogged())
		die("Non sei loggato");

    @$idmovie = $_GET['id'];
	echo json_encode(getPost($idmovie));
?>
