<?php

    //DOWNLOAD THE FILE
    #$content = file_get_contents('https://github.com/DangerBlack/homeflix/archive/master.zip');
    #file_put_contents("pack.zip",$content);

    //EXTRACTING THE ZIP FILE
    $zip = new ZipArchive;
    if ($zip->open('pack.zip') === TRUE) {
        $zip->extractTo('.');
        //CREATING THE DIRECTORY
        rename('homeflix-master','homeflix');
        mkdir('homeflix/archive/bigphoto');
        mkdir('homeflix/archive/photo');
        mkdir('homeflix/archive/tmdb');

        //MANAGING THE DATABASE
        rename('homeflix/archive/hf.sqlite.bkp','homeflix/archive/hf.sqlite');

        $HOME_DIR = "/home/danger/Scaricati/";
        $HIDDEN_PATH = '../glnkgganjagnokgaonawfa/';
        $TMDB_API_KEY = '4f37f56d80e68b3cd19981aed3ce0eaa';
        $DEFAULT_FOLDER ="MOVIE";

        $FOLDER_PATH=["MOVIE","MOVIE2"];
        $FOLDER_NAME=["FILM","Serie Tv"];

        if(!file_exists($HOME_DIR)){
            die("#002 ".$HOME_DIR." not found");
        }
        for($i=0;$i<count($FOLDER_PATH);$i++){
            if(!file_exists($HOME_DIR.$FOLDER_PATH)){
                die("#002 ".$HOME_DIR.$FOLDER_PATH" not found");
            }
        }

        //CONFIGURATING PHP
        $config = '<?php $HOME_DIR = "'.$HOME_DIR.'";'.PHP_EOL.
        '$HIDDEN_PATH = "'.$HIDDEN_PATH.'";'.PHP_EOL.
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

        $name="admin";
        $mail="admin.admin@admin.it";
        $pswd="123456";
        $role=0;
        insertUser($name,$mail,$pswd,$role);



    $zip->close();
    }else{
        die("#001 fail to open the zip packet!");
    }

 ?>
