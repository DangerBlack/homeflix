<?php

    //CHECK THE VARIABLE!
    @$INSTALL = $_POST['install'];
    if($INSTALL==true){
      error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
      ini_set('display_errors', 1);
      @set_time_limit(0);
      $HOME_DIR = $_POST['HOME_DIR'];//"/home/USERNAME/FOLDER_WITH_MOVIE/"
      $HIDDEN_PATH = $_POST['HIDDEN_PATH'];//'RANDOM_CARACTER_AS_THIS';
      $TMDB_API_KEY = $_POST['TMDB_API_KEY'];
      $DEFAULT_FOLDER =$_POST["DEFAULT_FOLDER"];

      $FOLDER_PATH=$_POST['FOLDER_PATH'];
      $FOLDER_NAME=$_POST['FOLDER_PATH'];

      $name=$_POST['name'];
      $mail=$_POST['mail'];
      $pswd="$_POST['pswd']";


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

      if(!function_exists('sqlite_open')) {
         die("#005 php-sqlite not installed!");
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

          //CREATING ROOT USER
          require_once("homeflix/php/query.php");
          $role=0;
          $res = insertUser($name,$mail,$pswd,$role);
          if($res==0){
            die("#004 unable to read or write on the database!");
          }

      }else{
          die("#001 fail to open the zip packet!");
      }
    }
 ?>
