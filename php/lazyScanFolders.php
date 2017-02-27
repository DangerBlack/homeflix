<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
    require_once("config.php");
    require_once("sanitizeMovieTitle.php");
    require_once("downloadImg.php");
    require_once("query.php");

    $ARCHIVE = "../archive/";

    $movie_list = getMovieHash();
    $movie_hash = [];
    foreach($movie_list as $movie){
        $movie_hash[$movie['hash']]=false;
    }
    //var_dump($movie_hash);

    function show_az($folder){
        global $HOME_DIR;
        global $ARCHIVE;
        global $movie_hash;
        $files = scandir($HOME_DIR.$folder);
        $dir=$HOME_DIR.$folder;
        $jresult= array();
        $i=0;
        foreach ($files as $node) {
            if($node != "." && $node != ".."){
                $complete_path = $dir."/".$node;
                if(is_dir($complete_path)){
                    $isdir = true;
                }else{
                    $isdir = false;
                }
                $temp=sanitize($node);
                $image = hash("md5",$node).".jpg";
                if(!file_exists($ARCHIVE.$image)){
                    download_img($temp,$image);
                }
                $hash= hash("md5",$node);
                $movie_hash[$hash]=true;
                if(!checkMovie($hash)){
                    insertMovie(hash("md5",$node),$temp,$node,$folder,filemtime($complete_path));
                    $i++;
                }
            }
        }
        return $i;
    }

    /*$res = [];
    foreach(array_keys($FOLDER_PATH) as $folder){
        $res[]=[$folder=>show_az($folder)];
    }*/
    $res = 0;
    foreach(array_keys($FOLDER_PATH) as $folder){
        $res+=show_az($folder);
    }

    foreach(array_keys($movie_hash) as $hash){
        //echo $hash." ".$movie_hash[$hash]."<br />";
        if($movie_hash[$hash]==false){
            //echo $hash;
            deleteMovie($hash);
            $res+=1;
        }
    }
    echo json_encode($res);

?>
