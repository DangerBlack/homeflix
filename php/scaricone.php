<?php
$HOME_DIR = "/media/FILM/FILM/";
$folder="TORRENT";
$ARCHIVE = "../archive/";
$files = scandir($HOME_DIR.$folder);
$dir=$HOME_DIR.$folder;
$jresult= array();
$i=0;
foreach ($files as $node) {
    if($node != "." && $node != ".."){
        $complete_path = $dir."/".$node;
        echo "touch \"".$node."\"<br />";
    }
}
 ?>
