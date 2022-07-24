<?php 

$_SESSION['pageClass'] = 'mono_x';
require_once('includes/inc_rpi_host_details.php');
require_once('includes/inc_mono_x_details.php');

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Troy Smith">

        <title>USB Share Management Console : Mono X Control</title>

        <!-- Bootstrap core CSS -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">


        
        <!-- Custom styles for this template -->
        <link href="/css/dashboard.css" rel="stylesheet">
        <link href="/css/circle.css" rel="stylesheet">

        <script src="/js/bootstrap.bundle.min.js"></script>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/progress-circle.js"></script>

        <link rel="preload" href="/img/progress.gif" as="image">
        
        <style>
            .content_block {
                float: left; 
                width:350px;
                margin-right:2em;
            }
            .pause_message {
                z-index:1000;
                position:absolute;
                width:350px;
                height:200px;
                line-height:200px;
                text-align:center;
                font-size:50px;
                color:red;
            }
            .mono_x_control {
                text-align:center;
                font-size:.7em;
                color:#0d6efd;
                width:70px;
                float:left;
            }
        </style>
    </head>
    <body >
   
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/index.php"><?php echo strtoupper(gethostname());?></a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </header>

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <?php include 'includes/navigation.php';?>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><img src="/img/PhotonMonoX.jpg" style="max-width:2em;filter: grayscale(.5);"> ANYCUBIC Mono X</h1>
                    </div>

                    <div id="content" class="content_block">
                        <div id="content_loading" class="show">
                            <p style="font-size:1.5em;color:#0d6efd;White-space: nowrap;">Connecting to the printer <img src='/img/progress.gif' style='height:1.5em;'></p> 
                        </div>
                        <div id="content_printer_status" class="hidden" style="font-size:1.2em;">
                            <table>
                                <tbody>
                                    <tr>
                                        <td rowspan="3"><img src="/img/PhotonMonoX.jpg" style="max-height:7em;"></td>  
                                        <td style='padding-right:2em;vertical-align: top;'>Printer&nbsp;IP: </td><td><span id="printer_ip"><?php echo $printer_ip_address; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td style='padding-right:2em;vertical-align: top;'>Connection: </td>
                                        <td><span id="printer_connection"><?php echo $printer_connection; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td style='padding-right:2em;padding-bottom:1em;vertical-align: top;'>Status: </td>
                                        <td style='padding-bottom:1em;vertical-align: top;'><span id="printer_status_1"><?php echo $printer_status; ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="content_print_details" class="hidden">            
                            <table style="margin-bottom:3em;width:350px;">
                                <tbody>
                                    <tr>
                                        <td style='text-align:center;font-size:1.5em;color:#0d6efd;padding-bottom:.75em;'><span id="printer_job"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">
                                        <div class="hidden" id="progress_gauge" style="position:relative;">
                                            <div id="circle" name="circle" style="margin-left:75px;"></div>
                                            <div id="paused_message" name="paused_message" class="pause_message hidden">PAUSED</div>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <div style="padding-left:105px;">
                                            <div id="pausePrinting_span" name="pausePrinting_span" class="mono_x_control hidden">
                                            PAUSE<br>
                                            <img id="pausePrinting" name="pausePrinting" type="image" src='/img/pause-circle.svg' class='icon icon_lg'>
                                            </div>
                                            <div id="resumePrinting_span" name="resumePrinting_span" class="mono_x_control hidden">
                                            RESUME<br>
                                            <img id="resumePrinting" name="resumePrinting" type="image" src='/img/play-circle.svg' class='icon icon_lg'>
                                            </div>
                                            <div id="stopPrinting_span" name="stopPrintingspan" class="mono_x_control hidden">
                                            STOP<br>
                                            <img id="stopPrinting" name="stopPrinting" type="image" src='/img/stop-circle.svg' class='icon icon_lg'>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <table style="margin-left:auto;margin-right:auto;">
                                            <tr><td style='padding-right:2em;vertical-align: top;'>Layers: </td><td><span id="layers_complete"></span></td></tr>
                                            <tr><td style='padding-right:2em;padding-bottom:1em;vertical-align: top;'>Resin&nbsp;Required: </td><td style='padding-bottom:1em;vertical-align: top;'><span id="resin_required"></span></td></tr>
                                            <tr><td style='padding-right:2em;vertical-align: top;'>Time&nbsp;Remaining: </td><td><span id="time_remaining"></span></td></tr>
                                            <tr><td style='padding-right:2em;vertical-align: top;'>Status: </td><td style='vertical-align: top;'><span id="printer_status_2"></span></td></tr>
                                        </table>
                                        <td>
                                    <tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php if(file_exists('/home/pi/.usb_share_resources/portal/enable_camera')) { ?>

                    <div style="float: left;max-width:350px;">
                        <img id="videoStream" src="" style="width:100%;">
                        <p>Note: Image reloades every 30 sec to release browser memory.</p>
                    </div>

                    <?php } ?>

                    <div style="clear:both;">

                    <div id="content_file_list" class="hidden">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2"><img src="/img/bootstrap-icons/printer.svg" style="height:1.2em;"> Printer Files</h1>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-sm" style="margin-bottom:4em;">
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th style="text-align:center;"><span style="font-size:.7em;line-height:.25;">Start<br>Print</span></th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                foreach ($fileList as $key => $val) {
                                $fileNames = explode("/",$val);

                                    if (strpos(strtolower($fileNames[0]), '.pwmx') !== false)
                                    {
                                        echo '<tr>
                                            <td>', $fileNames[0], '</td>
                                            <td style="text-align:center;"><img id="startPrinting' . $key . '" name="startPrinting' . $key . '" type="image" src="/img/play-circle.svg" class="icon icon_sm"></td>
                                            </tr>
                                            ';
                                    }
                                }

                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    <p id="status" style="display:none"></p>


    <script src="/js/usb_share_functions.js"></script>
    <script>
        window.onload = function afterWebPageLoad() { 
            document.getElementById("pausePrinting").addEventListener("click", pausePrinting);
            document.getElementById("stopPrinting").addEventListener("click", stopPrinting);
            document.getElementById("resumePrinting").addEventListener("click", resumePrinting);
            getPrinterStatus();
            window.setInterval("getPrinterStatus();", 3000);

            <?php if(file_exists('/home/pi/.usb_share_resources/portal/enable_camera')) { ?>
                reloadIMG();
                window.setInterval("reloadIMG();", 30000);
            <?php } ?>
        } 

        function setCircle(v) {
            var value = v;
            $('#circle').progressCircle({
            nPercent        : value,
            showPercentText : true,
            circleSize      : 200,
            thickness       : 6
            });
        }
        function updateCTRL(ctrl,some) {
            document.getElementById(ctrl).innerHTML = some;
        }
        function reloadIMG() {
            var temp = "http://<?php echo $ip_address ?>:8080/?action=stream&" + new Date().getTime();
            newImage = new Image();
            newImage.src = temp;
            document.getElementById("videoStream").src = newImage.src;
        }
        function monoXControl(action) {
            let req = new XMLHttpRequest();
            url = "/includes/util_mono_x.php?a=mono_x_action&cmd=" + action;
            req.open('GET', url);
            req.timeout = 2000;
            req.onload = function() {
            if (req.status == 200) {
                getPrinterStatus();
                response = this.responseText.trim();
                if(response.includes("ERROR")) {
                    alert("Error: Please make sure the printer is not waiting for input on the local control panel and platform is not in motion.");
                }
            }
            }
            req.send();
        }

        function pausePrinting() { monoXControl('pause'); }
        function resumePrinting() { monoXControl('resume'); }
        function stopPrinting() { monoXControl('stop'); }

        function getPrinterStatus() {
            btn_pause = document.getElementById("pausePrinting_span");
            btn_stop = document.getElementById("stopPrinting_span");
            btn_resume = document.getElementById("resumePrinting_span");
            progress_circle = document.getElementById("circle");
            paused_message = document.getElementById("paused_message");
            content_loading = document.getElementById("content_loading");
            content_printer_status = document.getElementById("content_printer_status");
            content_print_details = document.getElementById("content_print_details");
            content_file_list = document.getElementById("content_file_list");

            let req = new XMLHttpRequest();
            url = "/includes/util_mono_x.php?a=printer_status";

            req.open('GET', url, true);
            req.timeout = 2000;

            req.onload = function() {
                if (req.status == 200) {
                    //get json object
                    var json = this.responseText.trim();
                    var obj = JSON.parse(json);
                    //add current time to json
                    obj.refresh_time = new Date();
                    json = JSON.stringify(obj);

                    //update printer status
                    updateCTRL("printer_ip",obj.printer_ip_address);
                    updateCTRL("printer_connection",obj.printer_connection);
                    updateCTRL("printer_status_1",obj.printer_status);
                    updateCTRL("printer_status_2",obj.printer_status);

                    //update screen based on printer status

                    if(obj.printer_status == "Printing")
                    {
                        //update local storage
                        sessionStorage.setItem('last_print_details', json);

                        // show printer controls
                        content_printer_status.className = "hidden";
                        content_print_details.className = "show";
                        content_file_list.className = "hidden";

                        progress_gauge.classList.replace("hidden","show");
                        paused_message.classList.replace("show","hidden");
                        btn_pause.classList.replace("hidden","show");
                        btn_stop.classList.replace("hidden","show");
                        btn_resume.classList.replace("show","hidden");

                    } else if (obj.printer_status == "Paused")
                    {
                        //get current print details from local storage
                        try {
                            json = sessionStorage.getItem("last_print_details");
                            obj = JSON.parse(json);
                        } catch {}

                        // show printer controls
                        content_printer_status.className = "hidden";
                        content_print_details.className = "show";
                        content_file_list.className = "hidden";

                        progress_gauge.classList.replace("hidden","show");
                        paused_message.classList.replace("hidden","show");
                        btn_pause.classList.replace("show","hidden");
                        btn_stop.classList.replace("hidden","show");
                        btn_resume.classList.replace("hidden","show");
                    }  else if (obj.printer_status == "Stopped")
                    {
                        try{
                            sessionStorage.removeItem('last_print_details');
                        } catch {}
                        
                        // show printer controls
                        content_printer_status.className = "show";
                        content_print_details.className = "hidden";
                        content_file_list.className = "show";

                        progress_gauge.classList.replace("show","hidden");
                        paused_message.classList.replace("show","hidden");
                        btn_pause.classList.replace("show","hidden");
                        btn_stop.classList.replace("show","hidden");
                        btn_resume.classList.replace("show","hidden");
                    }

                    // display printer details
                    var name = obj.print_job_name;
                    var remaining = new Date(obj.seconds_remaining * 1000).toISOString().substr(11, 8);
                    var percent_complete = obj.percent_complete.replace("%","");

                    updateCTRL("printer_job",name);
                    updateCTRL("resin_required",obj.resin_required);
                    updateCTRL("layers_complete",obj.layers_complete);
                    updateCTRL("time_remaining",remaining);
                    setCircle(percent_complete); 

                    content_loading.className = "hidden";
                }
            }
            req.send();
        }
        <?php
        
            foreach ($fileList as $key => $val) {
                $fileNames = explode("/",$val);

                if (strpos(strtolower($fileNames[0]), '.pwmx') !== false)
                {
                    echo '
                    function startPrinting' . $key . '() {
                    action = \'print,' . $fileNames[1] . '\';
                    monoXControl(action);
                    }
                    document.getElementById("startPrinting' . $key . '").addEventListener("click", startPrinting' . $key . ');
                    ';
                }
            }
        ?>
    </script>
  </body>
</html>
