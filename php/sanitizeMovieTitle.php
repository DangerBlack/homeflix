<?php

    $special = ["BDRip","DIVX","BluRay","MIRCrew","HDTV","HDTS","CAM",
                "2013DTS"," -LIFE","Dual","DUAL"," LD ","XviD","BmA","OAV",
                "x264","1080p","subeng","itasub","720p","480p","avi","mkv","mp4",
                "multisub","Complete","iTALiAN","DVDRip","BrRip"," MD ","XviD-",
                "Shiv@","subs","HD","DTS","HQF","640kbps","6Ch","ZMachine","X265","BDRtL","Arlong"," EC ","WEB","DLMux","H264","TnT",
                "DVDR","Rip","TRL","EXTRA","site82","Pitt@Sk8","mutu","ViLLAiNS","GBM","TTN","JAP","CoWRY","KiNGDOM","AMIABLE","FLEET","PROPER","KILLERS",
                "BLUWORLD","UNCUT","mux","Sub","T4P3"."L@ZyMaN","anoXmous","Remastered","DIMENSION","AVS","HEVC","NAHOM","CRiME","WEBDL","m4Xxi","MAJESTiC",
                "TRL","720","ETRG","CREW","IDN","iCV","Fratposa","H 264","T4P3","UBi","R1D3Rs","LiFE",
                "-Republic","Twice","iDN_CreW","TeaM","LiAN","TSR","SiLENT","TrTd","AC3","ACC","AAC","--"];
    $special_exp = ["\.","\[([^\[])*\]","\(([^\[])*\)"];

    $language = ["Eng","Fra","Ita","Ger","Spa","Hun","Rus","Cz"];
    function sanitize($t){
        global $special;
        global $special_exp;
        global $language;

        $t = str_replace("."," ",$t);
        $t = str_replace("_"," ",$t);
        $t = str_replace("-"," ",$t);

        foreach($language as $s){
            $t = preg_replace("/[ |^]".$s."[ |$]/i"," ",$t);
        }
        foreach($special as $s){
            $t = str_ireplace($s," ",$t);
        }
        foreach($special_exp as $s){
            $t = preg_replace("/".$s."/","",$t);
        }

        $t = preg_replace("/[ *]/"," ",$t);

        $t = ucfirst($t);

        return $t;
    }

?>
