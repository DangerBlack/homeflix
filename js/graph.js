function loadGraph(){
    $.get("php/systemInfo.php",function(data){
        console.log(data);
        var js= JSON.parse(data);
        var chart = c3.generate({
            data: {
                bindto: '#chart',
                columns: [
                    ['Used Space', js['used_space']],
                    ['Free Space', js['free_space']],
                ],
                type : 'pie'
            }
        });
        $("#free_space").text(Math.floor(js['free_space']/1000000000*100)/100+" Gb");
        $("#used_space").text(Math.floor(js['used_space']/1000000000*100)/100+" Gb");
        $("#total_space").text(Math.floor(js['total_space']/1000000000*100)/100+" Gb");
        $("#cpu_temp").text(js['cpu_temp']+" °C");
        $("#uptime").text(js['uptime_D']+" "+js['uptime_H']);
        $("#load_15_minute").text(js['load_15_minute']+" %");
        $("#version").text(js['version']);
    });
}
