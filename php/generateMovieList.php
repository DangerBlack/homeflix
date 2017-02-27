<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
    include "config.php";
    include("sanitizeMovieTitle.php");
    include("downloadImg.php");
    include("query.php");

    $type=$_GET["type"];
	$folder="MOVIE";//$_GET["folder"];
    $ARCHIVE = "../archive/";

    function cmp($a, $b){
        return strcmp($b["filetime"], $a["filetime"]);
    }

    function show_az($folder){
        global $HOME_DIR;
        global $ARCHIVE;
        $key="key/".getSecretKey();
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
                echo $temp."\n";
                $jresult[$i]=["title"=>$temp,"href"=>$key."/".$folder."/".$node,"isdir"=>$isdir,"img"=>$image];
                insertMovie(hash("md5",$node),$temp,$node,$folder,filemtime($complete_path));
                $i++;
            }
        }
        return json_encode($jresult);
    }
    function show_time($folder){
        global $HOME_DIR;
        $key="key/".getSecretKey();
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
                $jresult[$i] = ["title"=>$temp,"filetime"=>filemtime($complete_path), "href"=>$key."/".$folder."/".$node,"isdir"=>$isdir,"img"=>hash("md5",$node).".jpg","img"=>$image];
                $i++;
            }
        }
        usort($jresult, "cmp");
        $jresult = array_slice($jresult, 0, 15);
        return json_encode($jresult);
    }

    //if($type=="order"){
    show_az($folder);
    echo "done";
    /*}else{
        echo show_time($folder);
    }*/


?>
