<?php
    //sudo a2enmod rewrite (abilitare mod rewrite per .htaccess)
    //sudo nano /etc/apache2/apache2.con (settare ALL le AllowOverride None)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once("query.php");
    require_once("config.php");
    require_once("sanitizeMovieTitle.php");
    $key=$_GET["key"];
    $movie=$_GET["movie"];
    //echo $key."<br />";
    //echo $movie."<br />";

    function show_az($key,$HOME_DIR,$folder){
        $files = scandir($HOME_DIR.$folder);
        $jresult= [];
        $i=0;
        foreach ($files as $node) {
            if($node != "." && $node != ".."){
                $complete_path = $HOME_DIR."/".$folder."/".$node;
                if(is_dir($complete_path)){
                    $isdir = true;
                }else{
                    $isdir = false;
                }
                //$title=sanitize($node);
                $jresult[$i]=["title"=>$node,"href"=>$node];
                $i++;
            }
        }
        return $jresult;
    }


    $attachment_location = $HOME_DIR.$movie;
    //$attachment_location = "../glnkgganjagnokgaonawfa/MOVIE/flag.txt";
    $attachment_location = $HIDDEN_PATH.$movie;
    if(checkSecretKey($key)){
        if(! is_dir($attachment_location)){
            $url = explode("/",$movie)[1];
            $idmovie = getMovieFromUrl($url);
            insertWatched($idmovie,$key); //RIATTIVARE!
            /*header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for internet explorer
            header("Content-Type: ".mime_content_type($attachment_location));
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:".filesize($attachment_location));
            header("Content-Disposition:; filename=\"".basename($attachment_location)."\"");
            /*readfile($attachment_location);*/
            $file_info = apache_lookup_uri($attachment_location);
            //header('content-type: ' . $file_info -> content_type);
            header("Content-Type: ".mime_content_type($attachment_location));
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:".filesize($attachment_location));
            if(virtual($attachment_location)){
                exit(0);
            }else{
                echo "Ops... failed to fetch granted file";
            }
            //header('X-Sendfile: '.$attachment_location);
            //header('Content-type: application/octet-stream');
            //header("Content-Transfer-Encoding: Binary");
            //header("Content-Length:".filesize($attachment_location));
            //header("Content-Disposition:; filename=\"".basename($attachment_location)."\"");
        }else{
            $myFile = "movieFolder.html";
            $fh = file_get_contents($myFile);
            echo $fh;
            $url = explode("/",$movie)[1];
            $res = show_az($key,$HOME_DIR,$movie);
            foreach($res as $r){
                echo '<p><a href="'.$url."/".$r['href'].'" >'.$r['title'].'</a></p>';
            }
            echo "</div></div></body></html>";
            //die("Sei in una cartella sorry!!");
        }
        //header("X-Sendfile: ".$attachment_location);
    }else{
        die("sorry");
    }

 ?>
