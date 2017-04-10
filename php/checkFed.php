<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	require_once("query.php");

    if(!isLogged())
		die("Non sei loggato");

    function get_url($request_url) {
		  $ch = curl_init();
		  curl_setopt($ch, CURLOPT_URL, $request_url);
		  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  $response = curl_exec($ch);
		  curl_close($ch);

		  return $response;
	}

    $id = $_GET['id'];

    $fed = getFedFromId($id);

    $res = get_url($fed['url']."/php/checkRemoteFed.php?secret=".$fed['secret']);

    echo $res;
?>
