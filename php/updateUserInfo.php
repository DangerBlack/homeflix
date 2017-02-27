<?php
	include("query.php");

	if(!isLogged())
		die("Non siete loggati");

	if(!isset($_POST['id'])){
		$id=getId();
	}else{
		$id=$_POST['id'];
	}
	$name=$_POST['name'];
	$mail=$_POST['mail'];
	$role=$_POST['role'];/*check privilege escalation*/
	$branca=$_POST['branca'];
	if(($id==getId())&&(getRole()<=$role)){//Mi sto cambiando il privilegio da solo (posso solo scendere di privilegio (numeri più grandi))
		if(updateUserInfo($id,$name,$mail,$role)!=0)
			echo 202;
		else
			echo 500;
	}else{
		$ruolo_originale=getUser($id)[0]['role'];
		if(($id!=getId())&&(getRole()<=$role)&&(($role<=$ruolo_originale)||(getRole()==0))){//sto cambiando il privilegio ad un altro posso solo farlo aumentare fino al mio livello (numeri più piccoli)
			if(updateUserInfo($id,$name,$mail,$role)!=0)
				echo 202;
			else
				echo 500;
		}else{
			echo 403;
		}
	}
?>
