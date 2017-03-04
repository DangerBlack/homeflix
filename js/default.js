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

function wrapUrlPost(post){
	post=post.replace(/(https?:\/\/)?[a-z0-9.]*\.[a-z0-9#\/?=]+/gi,function(x){
		var url=x;
		if(url.indexOf("http")!=0){
			url="http://"+url;
		}
		return '<a href="'+url+'" target="_blank" >'+x+'</a>';
	});
	return post;
}

function toHRData(data){
	if(data!=null){
		var split=data.split('-');
		if(split.length==3)
			return split[2]+'/'+split[1]+'/'+split[0];
	}
	return '';
}
function toDBData(data){
	if(data!=null){
		console.log(data);
		var split=data.split("/");
		if(split.length==3){
			data=split[2]+'-'+split[1]+'-'+split[0];
			console.log(data);
			return data;
		}
	}
	return '';
}
