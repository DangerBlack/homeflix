function loadInfo(hash){
    $.get("php/getInfo.php",{"hash":hash},function(data){
        var js=JSON.parse(data);
        $("#title").html(""+js.title);
        $("#stars").html("");
        if(js.vote_count!=null){
            $("#stars").attr("alt",js.vote_average+" - "+js.vote_count+" voti");
            $("#stars").attr("title",js.vote_average+" - "+js.vote_count+" voti");
        }
        for(var i=0;i<5;i++){
            if(i<js.vote_average/2){
                $("#stars").append('<span class="glyphicon glyphicon-star" aria-hidden="true"></span>');
            }else{
                $("#stars").append('<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>');
            }
        }
        if(js.release_date!=null){
            $("#year").html(js.release_date.split("-")[0]);
        }else {
            $("#year").html("");
        }
        $("#overview").html(js.overview);
        if(js.runtime!=null){
            $("#runtime").html(parseInt(js.runtime/60)+"h "+parseInt(js.runtime%60)+"m");
        }else{
            $("#runtime").html("");
        }
        if(js.backdrop_path!=null){
            $(".bgwide").css("background-image","url(\"archive/tmdb"+js.backdrop_path+"\")");
        }
        $("#watch").attr("href",'key/'+js.key+'/'+js.folder+'/'+js.url);

        $(".youtube").attr("href","https://www.youtube.com/results?search_query="+js.title.replace(" ","+")+"+trailer");

        if(js.watched.length==0){
            $("#lastseen").hide();
        }
        for(var i=0;i<js.watched.length;i++){
            $("#watched").append('<li><p><span>'+js.watched[i].name+'</span> <span>'+js.watched[i].time+'</span></p></li>')
        }
        $("#favorite").val(false);
        if(js.favorite==true){
            $("#favorite").val(true);
            $("#favorite").html('<span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Preferiti');
        }
        $("#favorite").click(function(){
            var value=$("#favorite").val();
            if(!value){
                $.get("php/addFavorite.php",{"id":js.id},function(data){
                    $("#favorite").html('<span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Preferiti');
                });
            }else{
                $.get("php/deleteFavorite.php",{"id":js.id},function(data){
                    $("#favorite").html('<span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span> Preferiti');
                });
            }
        });
    });
}
