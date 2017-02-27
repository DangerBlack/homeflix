<?php
	require_once('query.php');
	@$name=$_POST['name'];
	@$pswd=$_POST['pswd'];
	if(isLogged()){
		echo 200;
	}
	else{
		if(login($name,$pswd)){
			$expire=time()+60*60*24*30;
			setcookie("name", $name, $expire);
			setcookie("pswd", $pswd, $expire);
			echo 200;
		}
		else
			echo 403;
	}

?>
