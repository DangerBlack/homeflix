function initSettings(){
    var memRuolo=3;
    $(".error-message").hide();

    $(".editPhoto").click(function(){
        $(".photo").hide();
        $(".editPhoto").hide();
        $('#eventi-form').show();
    });
    $('#eventi-form').submit( function( e ) {
        console.log("Evento triggerato");
        $.ajax( {
          url: 'php/upload.php',
          type: 'POST',
          data: new FormData( this ),
          processData: false,
          contentType: false,
          success: function(data){
              console.log(data);
              alert("fatto!| "+data);
              location.reload();
            }
        } );
        e.preventDefault();
      } );

    $.get("php/getUserInfo.php",function(data){
        var js=JSON.parse(data);
        $(".photo").attr("src","archive/photo/"+js[0].img);
        $("#user").val(js[0].name);
        $("#mail").val(js[0].mail);
        memRuolo=js[0].role;
        $("#ruolo").html(getRuoloSelect(js[0].role));
    });
    $(".updatePswd").click(function(){
        var pswd=$("#oldpswd").val();
        var newpswd=$("#newpswd").val();
        var confirmpswd=$("#confirmpswd").val();
        if(newpswd!=confirmpswd){
            $(this).parent().find(".error-message").html('<p><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> le password non combaciano!</p>');
            $(this).parent().find(".error-message").show();
        }else{
            $.post('php/updatePswd.php',{'pswd':pswd,'newpswd':newpswd},function(data){
                if(data!=202){
                    $(this).parent().find(".error-message").html('<p><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> impossibile completare l\'operazione!</p>');
                    $(this).parent().find(".error-message").show();
                }else{
                    $(this).parent().find(".error-message").hide();
                    $("#oldpswd").val('');
                    $("#newpswd").val('');
                    $("#confirmpswd").val('');
                    location.reload();
                }
            });
        }
    });

    $(".updateInfo").click(function(){
        var name=$("#user").val();
        var mail=$("#mail").val();
        var role=$("#ruolo").val();
        if((name=="")||(mail=="")){
            $(this).parent().find(".error-message").html('<p><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> La mail o la password non sono stati definiti!</p>');
            $(this).parent().find(".error-message").show();
        }else{
            $.post('php/updateUserInfo.php',{'name':name,'mail':mail,'role':role},function(data){
                if(data!=202){
                    $(this).parent().find(".error-message").html('<p><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> impossibile completare l\'operazione!</p>');
                    $(this).parent().find(".error-message").show();
                }else{
                    //location.reload();
                }
            });
        }
    });

    $('#myModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data('whatever') // Extract info from data-* attributes
          // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
          // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
          var modal = $(this);
          //alert(recipient);
          if(recipient=="@addUser"){
            modal.find('.modal-title').html("Aggiungi un Utente");
            modal.find('.modal-body').html('<p class="space"><label>Username: </label> <input id="form-utente" class="form-control" type="text" placeholder="Username" /></p>'+
                                            '<p class="space"><label>mail: </label> <input id="form-mail" class="form-control" type="text" placeholder="name.surname@gmail.com" /></p>'+
                                            '<p class="space"><label>Password: </label> <input id="form-pswd" class="form-control" type="text" placeholder="password" /></p>'+
                                            '<p class="space"><label>Ruolo: </label><select id="form-ruolo" class="form-control">'+getRuoloSelect(memRuolo)+'</select></p>');

            $("#send").click(function(){
              var name=$("#form-utente").val();
              var mail=$("#form-mail").val();
              var pswd=$("#form-pswd").val();
              var role=$("#form-ruolo").val();
              addUser(name,mail,pswd,role);
            });
          }
          if(recipient=="@editUser"){
                var data=button.data("json").replace(/'/g,'"');
                console.log(data);
                var js=JSON.parse(data);

                modal.find('.modal-title').html("Modifica l'Utente id: "+js.id);
                modal.find('.modal-body').html('<p class="space"><label>Username: </label> <input id="form-utente" class="form-control" type="text" placeholder="Username" value="'+js.name+'" /></p>'+
                                                '<p class="space"><label>mail: </label> <input id="form-mail" class="form-control" type="text" placeholder="name.surname@gmail.com" value="'+js.mail+'"/></p>'+
                                                '<p class="space"><label>Ruolo: </label><select id="form-ruolo" class="form-control">'+getRuoloSelect(memRuolo)+'</select></p>');

                $("#form-ruolo").val(js.role);
                $("#send").click(function(){
                  var name=$("#form-utente").val();
                  var mail=$("#form-mail").val();
                  var pswd=$("#form-pswd").val();
                  var role=$("#form-ruolo").val();
                  $.post('php/updateUserInfo.php',{'id':js.id,'name':name,'mail':mail,'role':role},function(data){
                      if(data==202){
                        $('#myModal').modal('hide');
                      }
                  });
                });
          }
          if(recipient=="@addFed"){
            modal.find('.modal-title').html("Add a Federated Server");
            modal.find('.modal-body').html('<p class="space"><label>Name: </label> <input id="form-fed-name" class="form-control" type="text" placeholder="Federation alias" /></p>'+
                                            '<p class="space"><label>Url: </label> <input id="form-url" class="form-control" type="text" placeholder="http://127.0.0.1/homeflix" /></p>'+
                                            '<p class="space"><label>Secret: </label> <input id="form-secret" class="form-control" type="text" placeholder="password" /></p>');

            $("#send").click(function(){
              var name=$("#form-fed-name").val();
              var url=$("#form-url").val();
              var secret=$("#form-secret").val();
              addFed(name,url,secret);
            });
          }
    });
    $.get("php/getUserList.php",function(data){
        var js=JSON.parse(data);
        for(var i=0;i<js.length;i++){
            var p=js[i];
            $("#userList").append('<li class="media">'+
                                    '<div class="media-left">'+
                                        '<a href="#">'+
                                          '<img class="media-object" src="archive/photo/'+p.img+'" alt="photo">'+
                                        '</a>'+
                                    '</div>'+
                                    '<div class="media-body">'+
                                        '<h4 class="media-heading">'+p.name+' <button class="btn btn-xs btn-danger deleteUser" value="'+p.id+'" nome="'+p.name+'"> <span class="glyphicon glyphicon-trash"></span> </button></h4>'+
                                        '<p><button class="btn btn-sm btn-info" data-toggle="modal" data-target="#myModal" data-whatever="@editUser" data-json="'+JSON.stringify(p).replace(/"/g,"'")+'"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Change</button>'+
                                        '</p>'+
                                        '</div>'+
                                    '</div>'+
                               '</li>');
        }

        $(".deleteUser").click(function(){
            var id=$(this).val();
            var nome=$(this).attr('nome');
            var risp = prompt('Vuoi eliminare definitivamente l\'utente '+nome+'?\nIl processo è irreversibile!!!\nPer favore digita Si', "No");
            if ((risp == "si")||(risp == "Si")) {
                $.post('php/deleteUser.php',{'id':id},function(data){
                    if(data==204){
                        console.log("cancellato ! ");
                        location.reload();
                    }
                });
            }
        })
    });

    $.get("php/getFedList.php",function(data){
        var js=JSON.parse(data);
        for(var i=0;i<js.length;i++){
            var p=js[i];
            $("#fedList").append('<li class="media">'+
                                    '<div class="list-group-item">'+
                                        '<p>'+
                                          '<button class="btn btn-xs btn-danger deleteUser" value="'+p.id+'" nome="'+p.name+'"> <span class="glyphicon glyphicon-trash"></span> </button> '+
                                          '<span><b>'+p.name+':</b></span> '+
                                          '<span>'+p.url+'</span>'+
                                        '</p>'+
                                    '</div>'+
                               '</li>');
        }

        $(".deleteFed").click(function(){
            var id=$(this).val();
            var nome=$(this).attr('name');
            var risp = prompt('Do you like to erase the element '+name+'?\nProcess can not be inverted!!!\nPlease digit Yes', "No");
            if ((risp == "yes")||(risp == "Yes")) {
                $.post('php/deleteFed.php',{'id':id},function(data){
                    if(data==204){
                        console.log("deleted ! ");
                        location.reload();
                    }
                });
            }
        });

        $("#fedsecret").load("php/getFedSecret.php");
    });


}
function addUser(name,mail,pswd,role){
    $.post("php/addUser.php",{"name":name,"mail":mail,"pswd":pswd,"role":role},function(data){
        if(data==201){
            alert("User created succefully");
        }
    });
}

function addFed(name,url,secret){
    $.post("php/addFed.php",{"name":name,"url":url,"secret":secret},function(data){
        if(data==201){
            alert("Federation server added");
        }
    });
}
function getRuoloSelect(ruolo){
    var ruoli=["root","admin","user"];
    var s="";
    for(var i=ruolo;i<ruoli.length;i++){
        s=s+='<option value="'+i+'" >'+ruoli[i]+'</option>';
    }
    return s;
}
