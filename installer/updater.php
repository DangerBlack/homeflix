<?php
    $BASE_PATH = "..";
    if (!file_exists($BASE_PATH."/php/config.php")) {
        die("#007 config.php not found!!!");
    }
    require_once($BASE_PATH."/php/config.php");

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
            $res = deleteDirectory($old);
            if(!$res){
                echo "#004 ERROR: CAN NOT DELETE DIR! ".$old."\n";
            }
            return 1;
        }

        foreach ($old_f as $file) {
            //echo $file."\n";
            $complete_path_new = $new."/".$file;
            $complete_path_old = $old."/".$file;
            if($file != "." && $file != ".." && $file != $HIDDEN_PATH_TMP && $file!="archive" && $file!="config.php" && $file!="vendor" && $file!="pack.zip" && $file!="homeflix-master" && $file!="installer"){
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
                            $res = copy($complete_path_new,$complete_path_old);
                            if(!$res){
                                echo "#003 ERROR: CAN NOT COPY FILE! ".$complete_path_old."\n";
                            }
                            //copia il file!
                        }
                    }
                }
            }
        }

        if(count($new_f) > 2)
            echo "Esistono nuove ".(count($new_f) - 2)." cartelle/file non copiati\n";
        foreach($new_f as $file){
            if($file != "." && $file != ".." && $file!="archive"){
                $complete_path_new = $new."/".$file;
                $complete_path_old = $old."/".$file;
                $modifiche+= compare_folder($complete_path_old,$complete_path_new);
            }
        }

        return $modifiche;
    }
    function connect(){
      global $BASE_PATH;
      require_once($BASE_PATH."/php/Medoo.php");
      $database = new Medoo([
                // required
                'database_type' => 'sqlite',
                'database_file' => $BASE_PATH.'/archive/hf.sqlite'
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
      if(count($res)>0){
          return $res[0]['version'];
      }else{
          return 0;
      }
    }

    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }
        return rmdir($dir);
    }

    //DOWNLOAD THE FILE
    $content = file_get_contents('https://github.com/DangerBlack/homeflix/archive/master.zip');
    file_put_contents("pack.zip",$content);

    //EXTRACTING THE ZIP FILE
    $zip = new ZipArchive;
    if ($zip->open('pack.zip') === TRUE) {
        $zip->extractTo('.');
        $zip->close();
        $res = compare_folder($BASE_PATH,"homeflix-master");
        echo "modifiche fatte: ".$res."\n";

        $v = getVersion();

        @$update = scandir("homeflix-master/installer/update/");
        if(!$update){
            $update = [];
        }
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
        shell_exec("cd ".$BASE_PATH.";curl -sS https://getcomposer.org/installer | php");
        shell_exec("cd ".$BASE_PATH.";php composer.phar update");
        //shell_exec("cd ".$BASE_PATH.";composer update");

        if(!unlink("pack.zip")){
            die("#005 unable to erase the pack.zip!\n");
        }

        if(!deleteDirectory("homeflix-master")){
            die("#006 unable to erase the homeflix-master!\n");
        }
    }else{
        die("#001 fail to open the zip packet!\n");
    }
?>
