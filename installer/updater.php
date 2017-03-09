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
            return 0;
        }
        if(($new_f === false)){
            echo $new." non sono esistenti questi path!\n";
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
                    echo "CANCELLO FILE ".$complete_path_new."\n";
                    //cancella il file perchè non serve più
                }else{
                        if( sha1_file($complete_path_old) === sha1_file($complete_path_new)){
                            //nothing do do
                        }else{
                            $modifiche++;
                            echo "COPIO FILE ".$complete_path_new."\n";
                            //copia il file!
                        }
                    }
                }
            }
        }

        return $modifiche;
    }

    //DOWNLOAD THE FILE
    $content = file_get_contents('https://github.com/DangerBlack/homeflix/archive/master.zip');
    file_put_contents("pack.zip",$content);

    //EXTRACTING THE ZIP FILE
    $zip = new ZipArchive;
    if ($zip->open('pack.zip') === TRUE) {
        $zip->extractTo('.');
        $zip->close();
        echo "modifiche fatte: ".compare_folder("homeflix","homeflix-master");
        //verifica file aggiornamento e applica le novità del file di aggiornamento
        //php composer.phar update!!!


        //aggiungi il numero di versione al db

    }else{
        die("#001 fail to open the zip packet!");
    }
?>
