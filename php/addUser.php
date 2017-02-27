<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	include('query.php');
	if(!isLogged())
		die("Non sei loggato");
	$name=$_POST['name'];
	$mail=$_POST['mail'];
	$pswd=$_POST['pswd'];
	$role=$_POST['role'];/*check privilege escalation*/
	if(getRole()<=$role){
		if(insertUser($name,$mail,$pswd,$role)!=0)
			echo 201;
		else
			echo 500;
	}else{
		echo 403;
	}
?>
