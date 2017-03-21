<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
    //CHECK THE VARIABLE!
    @$INSTALL = $_POST['install'];
    if($INSTALL==true){

      @set_time_limit(0);
      $HOME_DIR = $_POST['HOME_DIR'];//"/home/USERNAME/FOLDER_WITH_MOVIE/"
      $HIDDEN_PATH = md5(microtime().rand());//$_POST['HIDDEN_PATH'];//'RANDOM_CARACTER_AS_THIS';
      $TMDB_API_KEY = $_POST['TMDB_API_KEY'];
      $DEFAULT_FOLDER =$_POST["DEFAULT_FOLDER"];

      $FOLDER_PATH=explode(',',$_POST['FOLDER_PATH']);
      $FOLDER_NAME=explode(',',$_POST['FOLDER_PATH']);

      $name=$_POST['name'];
      $mail=$_POST['mail'];
      $pswd=$_POST['pswd'];

      if(!file_exists($HOME_DIR)){
          die("#002 ".$HOME_DIR." not found");
      }
      for($i=0;$i<count($FOLDER_PATH);$i++){
          if(!file_exists($HOME_DIR.$FOLDER_PATH[$i])){
              die("#003 ".$HOME_DIR.$FOLDER_PATH[$i]." not found");
          }
      }

      if((count($FOLDER_PATH)!=count($FOLDER_NAME))||(count($FOLDER_PATH)<=0)){
        die("#007 Number of folder and name of folder are not the same number! You need at least have one folder!");
      }


      //CHECK THE SYSTEM!

      $version = SQLite3::version();
      if($version<=0) {
         die("#005 php5-sqlite (php5-cli/php5-dev/libsqlite3-0/libsqlite3-dev) not installed!");
      }
      if (version_compare(phpversion(), '5.4', '<')) {
          die("#006 php version is not enought high! Upgrade to 5.4 or upper");
      }

      //DOWNLOAD THE FILE
      $content = file_get_contents('https://github.com/DangerBlack/homeflix/archive/master.zip');
      file_put_contents("pack.zip",$content);

      //EXTRACTING THE ZIP FILE
      $zip = new ZipArchive;
      if ($zip->open('pack.zip') === TRUE) {
          $zip->extractTo('.');
          $zip->close();
          //CREATING THE DIRECTORY
          rename('homeflix-master','homeflix');
          mkdir('homeflix/archive/bigphoto');
          mkdir('homeflix/archive/photo');
          mkdir('homeflix/archive/tmdb');

          //MANAGING THE DATABASE
          rename('homeflix/archive/hf.sqlite.bkp','homeflix/archive/hf.sqlite');
          if(!chmod('homeflix/archive/hf.sqlite',0775)){
              die("#008 Unable to change permission on hf.sqlite!");
          }

          //SYMLINK SOLO SU LINUX!!!

          symlink($HOME_DIR,"homeflix/".$HIDDEN_PATH);

          //CONFIGURATING PHP
          $config = '<?php $HOME_DIR = "'.$HOME_DIR.'";'.PHP_EOL.
          '$HIDDEN_PATH = "../'.$HIDDEN_PATH.'/";'.PHP_EOL.
          '$TMDB_API_KEY = "'.$TMDB_API_KEY.'";'.PHP_EOL.
          '$DEFAULT_FOLDER ="'.$DEFAULT_FOLDER.'";'.PHP_EOL.
          '$FOLDER_PATH = ['.PHP_EOL;
          for($i=0;$i<count($FOLDER_PATH);$i++){
              $config=$config.'"'.$FOLDER_PATH[$i].'"=>"'.$FOLDER_NAME[$i].'"';
              if($i<count($FOLDER_PATH)-1)
              $config=$config.",".PHP_EOL;
          }
          $config=$config.PHP_EOL.'];?>';
          file_put_contents("homeflix/php/config.php",$config);


          //TESTING DB TODO
          require_once("homeflix/php/Medoo.php");
          function connect(){
      		$database = new Medoo([
                      // required
                      'database_type' => 'sqlite',
                      'database_file' => 'homeflix/archive/hf.sqlite'
              ]);
      		return $database;
          }
          function insertUser($name,$mail,$pswd,$role){
      		$database=connect();
              $salt = md5(microtime().rand());
      		$res=$database->insert("user",[
      			"name"=>$name,
      			"mail"=>$mail,
      			"pswd"=>hash('sha256',$pswd.$salt),
                  "salt"=>$salt,
      			"role"=>$role
      		]);
              $id=$database->id();
              if($id!=0){
                  $key = md5(microtime().rand());
                  $res2=$database->insert("secret",[
          			"iduser"=>$id,
          			"key"=>$key,
          			"active"=>true
          		]);
              }else{
                  die("500");
              }
      		return $res;
      	  }

          //CREATING ROOT USER
          $role=0;
          $res = insertUser($name,$mail,$pswd,$role);
          if($res==0){
            die("#004 unable to read or write on the database!");
          }

          if(!unlink("instal.php")){
              die("#009 unable to erase the installer!");
          }

          /*
          TODO install composer and update!!!
          curl -sS https://getcomposer.org/installer | php
          rm composer.lock
          php composer.phar install
          */

          shell_exec("curl -sS https://getcomposer.org/installer | php");
          unlink("composer.lock");
          shell_exec("php composer.phar install");


          //REMOVE pack.zip TODO

          die("201");
      }else{
          die("#001 fail to open the zip packet!");
      }
    }
 ?>
 <!DOCTYPE html>
 <html lang="en">
   <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
     <meta name="description" content="A mediacenter for home">
     <meta name="author" content="DangerBlack">
     <style>
     body {
       padding-top: 50px;
       background-color: black !important;
     }
     .shorten{
         margin-left:50px !important;
         margin-right:50px !important;
     }
     button.right{
     	float:right;
     	margin-bottom:2px;
     }
     .space{
     	margin-top:5px;
     }
     h1{
       color:red !important;
     }
     label{
       color:red !important;
     }
     p{
       color:white !important;
     }
     table th{
       color:red !important;
     }
     table td{
       min-width:200px;
     }
     .step1, .step2, .step3, .step4, .step5{
       display:none;
     }
     #errorWide{
       display:none;
       text-align:center;
     }

     </style>

     <title>HomeFlix Installer</title>
     <!-- Latest compiled and minified CSS -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

     <!-- Optional theme -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
     <!-- Latest compiled and minified JavaScript -->
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
     <script type="text/javascript" >
         function init(){
           $("#more").click(function(){
             var val=parseInt($(this).val());
             val=val+1;
             $("#FOLDER").append('<tr><td><input id="folder'+val+'" placeholder="MOVIES"/></td><td><input id="foldername'+val+'" placeholder="Movie"/></td></tr>');
             $(this).val(val);
           });
           function showError(error){
            	$("#error").text(error);
            	$("#errorWide").show();
            }
            function hideError(){
            	$("#errorWide").hide();
            }
            function validaCampi(step){//TODO check delle date etc etc
            	var res={};
            	res.ok=true;
            	res.error="none";
            	switch(step){
            		case 0:
            			var nome=$("#inName").val();
            			var mail=$("#inMail").val();
            			var pswd=$("#inPswd").val();
            			var pswd2=$("#inPswd2").val();
            			if(typeof(nome)==="undefined" || nome==""){
            				res.ok=false;
            				res.error="Name not defined";
            				return res;
            			}
            			if(typeof(mail)==="undefined" || mail==""){
            				res.ok=false;
            				res.error="Email not defined";
            				return res;
            			}
            			if(typeof(pswd)==="undefined" || pswd==""){
            				res.ok=false;
            				res.error="Password not defined";
            				return res;
            			}
                  if(typeof(pswd2)==="undefined" || pswd2==""){
            				res.ok=false;
            				res.error="Password not defined";
            				return res;
            			}
                  if(pswd2!==pswd){
            				res.ok=false;
            				res.error="Passwords mismetch";
            				return res;
            			}
            		break;
            		case 2:{
                  var HOME_DIR=$("#inHOME_DIR").val();
                  var TMDB_API_KEY=$("#inTMDB_API_KEY").val();
            			if(typeof(HOME_DIR)==="undefined" || HOME_DIR==""){
            				res.ok=false;
            				res.error="HOME_DIR not defined";
            				return res;
            			}
                  if(typeof(TMDB_API_KEY)==="undefined" || TMDB_API_KEY==""){
            				res.ok=false;
            				res.error="tmdb api key not defined";
            				return res;
            			}
            		}
                case 3:{
                  var val = parseInt($("#more").val());
                  for(var i=0;i<=val;i++){
                    var tmpF= $("#folder"+i).val();
                    var tmpN= $("#foldername"+i).val();
                    if(typeof(tmpF)==="undefined" || tmpF==""){
              				res.ok=false;
              				res.error="One of the folder path was left blank!";
              				return res;
              			}
                    if(typeof(tmpN)==="undefined" || tmpN==""){
              				res.ok=false;
              				res.error="One of the folder name was left blank!";
              				return res;
              			}
                  }
                }
            	}

            	return res;
            }
           $(".avanti").click(function(){
            		var totStep=3;
            		var step=$(this).attr("step");
            		var valida=validaCampi(parseInt(step));
                if(valida.ok){
            			hideError();
            			$(".step"+step).hide();
            			step++;
            			$(".step"+step).show();
            			var valeur=parseInt(step*100/totStep);
            			$('#progress-bar').css('width', valeur+'%').attr('aria-valuenow', valeur);
            			$('#progress-bar').text(valeur+"%");
            			if(step==totStep){
            				createNew();
            			}
            		}else{
            			showError(valida.error);
            		}
            });
            function createNew(){
              var dati={};
              dati["install"]=true;
              dati["name"]=$("#inName").val();
              dati["mail"]=$("#inMail").val();
              dati["pswd"]=$("#inPswd").val();
              dati["HOME_DIR"]=$("#inHOME_DIR").val();
              dati["TMDB_API_KEY"]=$("#inTMDB_API_KEY").val();
              dati["DEFAULT_FOLDER"]=$("#folder0").val();
              var tempFolder="";
              var tempFolderName="";
              var val = parseInt($("#more").val());
              for(var i=0;i<=val;i++){
                var tmpF= $("#folder"+i).val();
                var tmpN= $("#foldername"+i).val();
                tempFolder+=tmpF+",";
                tempFolderName+=tmpN+",";
              }
              tempFolder=tempFolder.substring(0, tempFolder.length - 1);
              tempFolderName=tempFolderName.substring(0, tempFolderName.length - 1);

              dati["FOLDER_PATH"]=tempFolder;
              dati["FOLDER_NAME"]=tempFolderName;

              $.post("instal.php",dati,function(data){
             		if(data==201){
                        alert("Everything is purrfect!");
                        location.reload("homeflix/index.html");
             			//TODO In realtà non fa nulla... gestire errori
             		}else{
                       showError(data);
                       for(var i=1;i<=3;i++)
                            $(".step"+i).hide();
                       $(".step0").show();
                   }
             });
            }
         }
         $(document).ready(function(){
           init();
         });
     </script>
   </head>

   <body>

     <nav class="navbar navbar-inverse navbar-fixed-top">
       <div class="container-fluid shorten">
         <div class="navbar-header">
           <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
             <span class="sr-only">Toggle navigation</span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
           </button>
           <a class="navbar-brand" href="#">HomeFlix</a>
         </div>
         <div id="navbar" class="collapse navbar-collapse">
         </div><!--/.nav-collapse -->
       </div>
     </nav>

     <div class="container-fluid shorten">

       <div class="starter-template">
         <h1 id="foldername">Installer</h1>
         <div class="row">
      			<div class="progress">
      			  <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
      				0%
      			  </div>
      			</div>
      			<div id="errorWide" class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> <span id="error"></span></div>
      		</div>
      		<div class="row step0">
        			<div class="col-lg-12">
        				<div class="well well-sm"><strong><span class="glyphicon glyphicon-asterisk"></span> Those fileds are required</strong></div>
        				<div class="form-group">
        					<label>Username</label>
        					<div class="input-group">
        						<input type="text" class="form-control" id="inName" placeholder="Username" required>
        						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
        					</div>
        				</div>
        				<div class="form-group">
        					<label for="InputEmail">E-Mail</label>
        					<div class="input-group">
        						<input type="text" class="form-control" id="inMail" placeholder="E-mail address" required>
        						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
        					</div>
        				</div>
        				<div class="form-group">
        					<label for="InputEmail">Password</label>
        					<div class="input-group">
        						<input type="password" class="form-control" id="inPswd" placeholder="password">
        						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
        					</div>
        				</div>
                <div class="form-group">
        					<label for="InputEmail">Check Password</label>
        					<div class="input-group">
        						<input type="password" class="form-control" id="inPswd2" placeholder="password">
        						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
        					</div>
        				</div>
        				<div class="form-group">
        					<button step="0" value="Next" class="btn btn-lg btn-primary pull-right avanti" ><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> next</button>
        				</div>
        			</div>
          </div>

          <div class="row step1">
        			<div class="col-lg-12">
        				<div class="well well-sm"><strong><span class="glyphicon glyphicon-asterisk"></span> Those fileds are required</strong></div>
        				<div class="form-group">
        					<label>Home movie directory</label>
        					<div class="input-group">
        						<input type="text" class="form-control" id="inHOME_DIR" placeholder="/home/USERNAME/FOLDER_WITH_MOVIE/" required>
        						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
        					</div>
        				</div>
        				<div class="form-group">
        					<label for="InputEmail">The movie db api key <a href="https://www.themoviedb.org/?language=en" target="_blank"> <span class="glyphicon glyphicon-info-sign"></span></a></label>
        					<div class="input-group">
        						<input type="text" class="form-control" id="inTMDB_API_KEY" placeholder="TMDB Api key" required>
        						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
        					</div>
        				</div>
        				<div class="form-group">
        					<button step="1" value="Next" class="btn btn-lg btn-primary pull-right avanti" ><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> next</button>
        				</div>
        			</div>
          </div>

          <div class="row step2">
        			<div class="col-lg-12">
                <p>
                  Add one per line the folder inside movie path selected in the previous view!<br />
                  Only the folder choosen here will be shown in the program!<br />
                  Add an alias in order to hide the true path of the folder
                </p>
                <table id="FOLDER">
                  <tr>
                    <th>FOLDER PATH ON SERVER</th>
                    <th>FOLDER NAME</th>
                  </tr>
                  <tr>
                    <td>
                      <input id="folder0" placeholder="MOVIES"/>
                    </td>
                    <td>
                      <input id="foldername0" placeholder="Movie"/>
                    </td>
                  </tr>
                </table>
                <button id="more" class="btn btn-primary" value="0"><span class="glyphicon glyphicon-menu-plus" aria-hidden="true">add folder</span></button>
                <div class="form-group">
        					<button step="2" value="Send" class="btn btn-lg btn-success pull-right avanti" ><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> next</button>
        				</div>
              </div>
          </div>

       </div>

     </div><!-- /.container -->
   </body>
 </html>
