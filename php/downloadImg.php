<?php
    include "lib/simple_html_dom.php";
    $PATH="../archive/";
    $title = "300 L Alba Di Un Impero 2014";
    $hash="d5792477c8927603deeb426fc05d19e7.jpg";

    function download_img($title,$hash){
        global $PATH;
        try{
            $title = str_replace(" ","+",$title);

            $search_query = urlencode( $title );
            //echo "https://www.google.com/search?q=".$title."&tbm=isch&gws_rd=cr&ei=CyOmWJjTDYbtauSCrrgF\n";
            $html = file_get_html( "https://www.google.com/search?q=".$title."&tbm=isch&gws_rd=cr&ei=CyOmWJjTDYbtauSCrrgF" );
            //echo $html;
            if(is_object($html)){
                $images = $html->find('img',0);
                $preview= $images->src;

                $content = @file_get_contents($preview);
                if($content !==FALSE){
                    file_put_contents($PATH.$hash,$content);
                }
            }
        }catch(Exception $e){

        }
    }
    /*$quality=$images->parent()->href;
    echo $quality."\n";
    $init= 'imgurl=';
    $init_p= strrpos($init,$quality);
    echo $init_p."\n";
    $end='&imgrefurl';
    $end_p = strrpos($end,$quality,$init+1);
    $quality = substr($quality,$init_p,$end_p-$init_p);
    $quality = urldecode($quality);
    echo $quality;*/

    //download_img($title,$hash);
?>
