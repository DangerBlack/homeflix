function generateStar(vote){
    var s='';
    for(var i=0;i<5;i++){
        if(i<vote){
            s+=('<span class="glyphicon glyphicon-star" aria-hidden="true"></span>');
        }else{
            s+=('<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>');
        }
    }
    return s;
}

function loadInfo(hash){
    var id;
    $.get("php/getInfo.php",{"hash":hash},function(data){
        var js=JSON.parse(data);
        id=js.id;
        $("#title").html(""+js.title);
        $("#stars").html("");
        loadPost(id);
        if(js.vote_count!=null){
            $("#stars").attr("alt",js.vote_average+" - "+js.vote_count+" voti");
            $("#stars").attr("title",js.vote_average+" - "+js.vote_count+" voti");
        }
        /*for(var i=0;i<5;i++){
            if(i<js.vote_average/2){
                $("#stars").append('<span class="glyphicon glyphicon-star" aria-hidden="true"></span>');
            }else{
                $("#stars").append('<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>');
            }
        }*/
        $("#stars").append(generateStar(js.vote_average/2));
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

        $(".details").attr("href","https://www.google.com/search?q="+js.title.replace(" ","+")+"");

        if(js.watched.length==0){
            $("#lastseen").hide();
        }
        for(var i=0;i<js.watched.length;i++){
            $("#watched").append('<li><p><span>'+js.watched[i].name+'</span> <span>'+js.watched[i].time+'</span></p></li>')
        }
        $("#favorite").val(false);
        if(js.favorite==true){
            $("#favorite").val(true);
            $("#favorite").html('<span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Favorite');
        }

        $("#mylist").val(false);
        if(js.mylist==true){
            $("#mylist").val(true);
            $("#mylist").html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> MyList');
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

        $("#mylist").click(function(){
            var value=$("#mylist").val();
            if(!value){
                $.get("php/addMyList.php",{"id":js.id},function(data){
                    $("#mylist").html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> MyList');
                });
            }else{
                $.get("php/deleteMyList.php",{"id":js.id},function(data){
                    $("#mylist").html('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> MyList');
                });
            }
        });

        $.get("php/getMovieQuality.php",{"movie":hash},function(data){
            var js=JSON.parse(data);
            //var k =Object.keys(js);
            for(var i=0;i<js.length;i++){
                if(js[i].info.vote>=7){
                    $("#moviequality").append('<span class="qinfo qgood" title="'+js[i].info.short+'" >'+js[i].name+'</span>');
                }else{
                    $("#moviequality").append('<span class="qinfo qbad" title="'+js[i].info.short+'" >'+js[i].name+' <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></span>');
                }
            }
        });

    });

    $('#myModal').on('show.bs.modal', function (event) {
    	  var button = $(event.relatedTarget) // Button that triggered the modal
    	  var recipient = button.data('whatever') // Extract info from data-* attributes
    	  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    	  var modal = $(this);
    	  //alert(recipient);
    	  if(recipient=="@Commento"){
    		commentoModal(id,modal);
    	  }
    	  if(recipient.indexOf("EditCommento")!=-1){
    		  var idCommento=recipient.split('-')[1];
    		  editCommentoModal(id,modal,idCommento);
    	  }
    });

    function commentoModal(id,modal){
    	modal.find('.modal-title').html("Write a comment");
    	modal.find('.modal-body').html(
            '<div class="col-md-6">'+
            '<label>Message: </label>'+
            '</div>'+
            '<div class="col-md-6">'+
            '<span><b>Star: </b></span><select id="selectedStar"><option value="null">none</option><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>'+
            '</div>'+
            '<div class="col-md-12">'+
            '<textarea id="commento" type="text" placeholder="-text-" ></textarea><span class="rightText">#word: <span id="counter">0</span><span>/500</span></span>'+
            '</div>');

    	$("#commento").keypress(function(){
    		var l=$(this).val().length;
    		if(l>500){
    			$("#counter").css("color","red");
    		}else{
    			$("#counter").css("color","black");
    		}
    		$("#counter").html(l);
    	});
    	$("#send").click(function(){
    	  var mex=$("#commento").val();
    	  var stars=$("#selectedStar").val();
    	  addPost(id,mex,stars);
    	});
    }
    function addPost(id,mex,stars){
    	$.post("php/addPost.php",{"id":id,"mex":mex,"star":stars},function(data){
    		if(data=="201"){
    			$('#myModal').modal('hide');
    			location.reload();
    		}
    	});
    }

    function loadPost(id){
        $.get("php/getPost.php",{"id":id},function(data){
            var js=JSON.parse(data);
            for(var i=0;i<js.length;i++){
                var p=js[i];

                var post=p.mex.replace(/\n/g,"<br />");
                post=wrapUrlPost(post);
                $("#postlist").append(
                '<div class="media">'+
                    '<div class="media-left">'+
                    '<a href="#">'+
                      '<img class="media-object" src="archive/photo/'+p.img+'" alt="'+p.name+'">'+
                    '</a>'+
                 '</div>'+
                  '<div class="media-body">'+
                    '<h4 class="media-heading">'+p.name+' '+generateStar(p.star)+'</h4>'+
                    '<p>'+post+'</p>'+
                    '<p class="small"><i>'+p.time+'</i></p>'+
                  '</div>'+

                '</div>')
            }
        });
    }

}
