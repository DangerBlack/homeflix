var mem_key="";
function loadKey(callback){
    $.get("php/getKey.php",function(key){
        mem_key=key;
        callback(key);
    });
}
function loadMovie(key,folder,order){
    $.get("php/getMovieList.php",{"order":order,"folder":folder},function(data){
        $("#movielist").html("");
        if(order=="time" && folder==""){
            $("#foldername").text("Section new releases");
        }else
        if(order == "time" && folder=="mylist")
        {
            $("#foldername").text("MyList");
        }else{
            $("#foldername").text("Section "+global_folder[folder]);
        }
        var js=JSON.parse(data);
        if(js.length==0){
            $("#movielist").append("<h2>Woops... sems this folder is empty!</h2>")
        }

        var cidx=-1;
        for(var i=0;i<js.length;i++){
            if(i%6==0){
                cidx++;
                $("#movielist").append('<div id="r_'+cidx+'"class="row vpadding"></div>');
            }
            var backdrop_path='archive/'+js[i].hash+'.jpg';
            if(js[i].backdrop_path!=null){
                backdrop_path='archive/tmdb'+js[i].backdrop_path+'';
            }
            //alert(backdrop_path);
            $("#r_"+cidx).append('<div class="col-md-2 nopadding">'+
                                    "<a class=\"movie\" href=\"key/"+key+"/"+js[i].folder+"/"+js[i].url+"\" style=\"background-image: url("+backdrop_path+")\" >"+
                                        '<p>'+js[i].title+'</p>'+
                                        '<span class="play glyphicon glyphicon-play-circle"></span>'+

                                    '</a>'+
                                    '<div class="more"><a href="info.php?movie='+js[i].hash+'" class="big"><span class="glyphicon glyphicon-chevron-down" ></span></a></div>'+
                                '</div>');

        }
        $(".play").hide();
        $(".more").hide();
        $("a.movie").mouseenter(function(){
            $(this).children(".play").show();
            $(this).next(".more").show();
        });
        $("a.movie").mouseleave(function(){
            console.log("entratoaaa!");
            $(this).children(".play").hide();
            $(this).next(".more").hide();
        });
        $(".more").mouseenter(function(){
            $(this).prev().children(".play").show();
            $(this).show();
        })
        $(".more").mouseleave(function(){
            console.log("entratoaaa!");
            $(this).prev().children(".play").hide();
            $(this).hide();
        });
    });
}

function loadWrap(key,folder,order){
    loadMovie(key,folder,order);
    $("#movieFolder").children("li").removeClass("active");
    $("."+folder).addClass("active");
}

function getMovieFolder(key){
	$.post("php/getFolderList.php",function(data){
		var js=JSON.parse(data);
		var folder = Object.keys(js);
        global_folder = js;
		for(var i=0;i<folder.length;i++){//TODO funzione wrapper per cambiare la scheda active e cambiare il sottotitolo della pagina
			$("#movieFolder").append('<li class="'+folder[i]+'"><a href="#" onclick=loadWrap(\''+key+'\',\''+folder[i]+'\',\'name\')>'+js[folder[i]]+'</a></li>');
		}
	});
}
