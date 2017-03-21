<?php
    require_once("homeflix/php/config.php");

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
    @set_time_limit(0);
    function compare_folder($old,$new){
        global $HIDDEN_PATH;
        $HIDDEN_PATH_TMP = explode("/",$HIDDEN_PATH)[1];
        $modifiche = 0;
        echo "path old: ".$old."\n";
        echo "path new: ".$new."\n";

        @$old_f = scandir($old);
        @$new_f = scandir($new);

        if($old_f === false){
            echo "Crea cartella!!!\n";
            mkdir($old, 0775);
            return 0;
        }
        if(($new_f === false)){
            echo $new." non sono esistenti questi path!\n";
            $res = rmdir($old);
            if(!$res){
                echo "#004 ERROR: CAN NOT DELETE DIR! ".$old."\n";
            }
            return 0;
        }

        foreach ($old_f as $file) {
            //echo $file."\n";
            $complete_path_new = $new."/".$file;
            $complete_path_old = $old."/".$file;
            if($file != "." && $file != ".." && $file != $HIDDEN_PATH_TMP && $file!="archive"){
                if(is_dir($complete_path_old)){
                    $modifiche+= compare_folder($complete_path_old,$complete_path_new);
                }else{
                if(! in_array($file, $new_f)){
                    $modifiche++;
                    echo "DELETE FILE ".$complete_path_old."\n";
                    $res = unlink($complete_path_old);
                    if(!$res){
                        echo "#002 ERROR: CAN NOT DELETE FILE! ".$complete_path_old."\n";
                    }
                    //cancella il file perchè non serve più
                }else{
                        $key = array_search($file, $new_f);
                        unset($new_f[$key]);
                        if( sha1_file($complete_path_old) === sha1_file($complete_path_new)){
                            //nothing do do
                        }else{
                            $modifiche++;
                            echo "COPY FILE ".$complete_path_new."\n";
                            $res = unlink($complete_path_new,$complete_path_old);
                            if(!$res){
                                echo "#003 ERROR: CAN NOT COPY FILE! ".$complete_path_old."\n";
                            }
                            //copia il file!
                        }
                    }
                }
            }
        }

        echo "Esistono nuove ".count($new_f)." cartelle/file non copiati\n";
        foreach($new_f as $file){
            $complete_path_new = $new."/".$file;
            $complete_path_old = $old."/".$file;
            $modifiche+= compare_folder($complete_path_old,$complete_path_new);
        }

        return $modifiche;
    }
    function connect(){
      require_once("homeflix/php/Medoo.php");
      $database = new Medoo([
                // required
                'database_type' => 'sqlite',
                'database_file' => 'homeflix/archive/hf.sqlite'
        ]);
      return $database;
    }
    function getVersion(){
      $database=connect();
      $res=$database->select("version",[
          "id",
          "version"
      ],[
          "post.status[=]"=>1,
          "ORDER"=>["time"=>"DESC"],
      ]);

      return $res[0]['version'];
    }

    //DOWNLOAD THE FILE
    $content = file_get_contents('https://github.com/DangerBlack/homeflix/archive/master.zip');
    file_put_contents("pack.zip",$content);

    //EXTRACTING THE ZIP FILE
    $zip = new ZipArchive;
    if ($zip->open('pack.zip') === TRUE) {
        $zip->extractTo('.');
        $zip->close();
        $res = compare_folder("homeflix","homeflix-master");
        echo "modifiche fatte: ".$res;

        $v = getVersion();

        @$update = scandir("homeflix-master/installer/update/");

        foreach ($update as $file) {
            if($file != "." && $file != ".."){
                $num = explode("_",$file)[1];  //FILE SHOULD HAVE THIS FORM update_1.0_v.php => 1.0 versione
                $num = floatval($num);
                if($num>$v){
                    echo "INSTALLING THE VERSION ".$file."\n";
                    include("homeflix-master/installer/update/".$file);//RUN THE PROCESS
                    echo "INSTALLED \n";
                }
            }
        }

        //php composer.phar update!!!


        if(!unlink("pack.zip")){
            die("#005 unable to erase the pack.zip!");
        }

        if(!rmdir("homeflix-master")){
            die("#006 unable to erase the homeflix-master!");
        }

    }else{
        die("#001 fail to open the zip packet!");
    }
?>
