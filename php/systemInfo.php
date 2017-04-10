<?php
    require_once("config.php");
    require_once("query.php");

    if(!isLogged())
        die("403");

    $free_space = disk_free_space($HOME_DIR);
    $total_space = disk_total_space($HOME_DIR);
    $used_space = $total_space-$free_space;
    $percentage_free = $free_space ? round($free_space / $total_space, 2) * 100 : 0;
    $uptime_result = exec("uptime");

    if(substr_count(",",$uptime_result)>4){
        $uptime_result = explode(",",$uptime_result);
        $uptime_D = $uptime_result[0];
        $uptime_H = $uptime_result[1];
        $users = $uptime_result[2];
        $load_1_minute = $uptime_result[3];
        $load_5_minutes = $uptime_result[4];
        $load_15_minutes = $uptime_result[5];
    }
    else{
		 $uptime_result = explode(",",$uptime_result);
         $uptime_D = "0 Days";
         $uptime_H = $uptime_result[0];
         $users = $uptime_result[1];
         $load_1_minute = $uptime_result[2];
         $load_5_minutes = $uptime_result[3];
         $load_15_minutes = $uptime_result[4];

	 }
    $cpu_temp = exec('cat /sys/class/thermal/thermal_zone0/temp');
    $cpu_temp = round($cpu_temp/1000,1);

    $res=[
        "free_space"=>$free_space,
        "used_space"=>$used_space,
        "total_space"=>$total_space,
        "percentage_free"=>$percentage_free,
        "uptime_H"=>$uptime_H,
        "uptime_D"=>$uptime_D,
        "cpu_temp"=>$cpu_temp,
        "load_1_minute"=>$load_1_minute,
        "load_5_minute"=>$load_5_minutes,
        "load_15_minute"=>$load_15_minutes,
        "version"=>getVersion()
    ];

    echo json_encode($res);
?>
