function isLogged(){
	$.post("php/login.php",function(data){
		if(data==200){
			$(".dropdown-menu").append('<li role="separator" class="divider"></li>'+
			'<li><a href="#" onclick="logout()"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Logout</a></li>');
			lazyScanFolders();
		}else{
			location.replace("login.html");
		}
	});
}
function logout(){
	$.post("php/logout.php",function(data){
		if(data==200){
			location.replace("login.html");
		}else{

		}
	});
}

function lazyScanFolders(){
	$.get("php/lazyScanFolders.php",function(data){
		if(data>0){
			//alert("Nuovi contenuti rilevati!");
			location.reload();
		}
	});
}
