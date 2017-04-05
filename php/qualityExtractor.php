<?php

    $quality = [
        "BDRip"=>[
                    "short"=>"Blue Ray Quality",
                    "vote"=>8
                ],
        "BRRip"=>[
                    "short"=>"Blue Ray Quality",
                    "vote"=>8
                ],
        "DTTRip"=>[
                    "short"=>"Digital Television Quality",
                    "vote"=>6.5
                ],
        "DVD RIP"=>[
                    "short"=>"DVD Quality",
                    "vote"=>7
                ],
        "DVD SCR"=>[
                    "short"=>"DVD Quality",
                    "vote"=>7.5
                ],
        "FS"=>[
                    "short"=>"Fullscreen",
                    "vote"=>6
                ],
        "HDTV RIP"=>[
                    "short"=>"BlueRay Quality",
                    "vote"=>8
                ],
        "HQ"=>[
                    "short"=>"BlueRay Quality",
                    "vote"=>8
                ],
        "PD TV"=>[
                    "short"=>"Television Quality",
                    "vote"=>6.5
                ],
        "SAT RIP"=>[
                    "short"=>"Television Quality",
                    "vote"=>6.5
                ],
        "DVBRip"=>[
                    "short"=>"Television Quality",
                    "vote"=>6.5
                ],
        "R5"=>[
                    "short"=>"DVD Quality but hard text",
                    "vote"=>7
                ],
        "R6"=>[
                    "short"=>"From Asia",
                    "vote"=>6
                ],
        "TS"=>[
                    "short"=>"Cam Quality",
                    "vote"=>6.5
                ],
        "TC"=>[
                    "short"=>"Better than TS",
                    "vote"=>7
                ],
        "TV RIP"=>[
                    "short"=>"Television Quality",
                    "vote"=>6.5
                ],
        "VHS RIP"=>[
                    "short"=>"VHS Quality",
                    "vote"=>6
                ],
        "VHSSCR"=>[
                    "short"=>"VHS Quality",
                    "vote"=>6
                ],
        "WS"=>[
                    "short"=>"WideScreen 16:9",
                    "vote"=>8
                ],
        "AAC"=>[
                    "short"=>"Better Audio Quality",
                    "vote"=>8
                ],
        "AC3"=>[
                    "short"=>"Better Audio 5.1 Quality",
                    "vote"=>8
                ],
        "DD"=>[
                    "short"=>"Audio from cinema disk",
                    "vote"=>7
                ],
        "DSP"=>[
                    "short"=>"Audio from digital signal",
                    "vote"=>7.5
                ],
        "DTS"=>[
                    "short"=>"Audio from DTS2 disk",
                    "vote"=>8
                ],
        "LC"=>[
                    "short"=>"Low-complexity audio",
                    "vote"=>7
                ],
        "LD"=>[
                    "short"=>"Audio from camera",
                    "vote"=>7
                ],
        "MD"=>[
                    "short"=>"Audio from microphone",
                    "vote"=>4
                ],
        "MP3"=>[
                    "short"=>"Good codec audio",
                    "vote"=>7.5
                ],
        "XViD"=>[
                    "short"=>"Codec audio XViD",
                    "vote"=>7.5
                ],
        "720p"=>[
                    "short"=>"Video 720p quality",
                    "vote"=>7
                ],
        "1080p"=>[
                    "short"=>"Video 1080p quality",
                    "vote"=>8
                ],
        "DVD5"=>[
                    "short"=>"DVD quality 4.7GB",
                    "vote"=>6
                ],
        "DVD9"=>[
                    "short"=>"DVD quality 8.5GB",
                    "vote"=>6.5
                ],
        "INTERNAL"=>[
                    "short"=>"Audio imperfection",
                    "vote"=>5
                ],
        "PROPER"=>[
                    "short"=>"Fixed version of previous release",
                    "vote"=>8
                ],
        "WEB-DL"=>[
                    "short"=>"Video from iTunes 720p-1080p",
                    "vote"=>7
                ],
        "WEBRip"=>[
                    "short"=>"Video from youtube",
                    "vote"=>6
                ],
        "WS"=>[
                    "short"=>"Video 16:9",
                    "vote"=>8
                ],
    ];


    function checkQuality($t){
        global $quality;
        $info=[];
        foreach(array_keys($quality) as $q){
            if(strpos($t,$q)!=false){
                $info[] = ["name"=>$q, "info"=>$quality[$q]];
            }
        }

        return $info;
    }
?>
