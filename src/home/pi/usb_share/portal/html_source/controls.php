
<?php 

$_SESSION['pageClass'] = 'tools';
require_once('includes/inc_rpi_host_details.php');

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Troy Smith">

    <title>USB Share Management Console : Power / Control</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
  </head>
  <body >
   
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/index.php"><?php echo $hostname; ?></a>
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
          <div id="div_content_forms"  class="show">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2 class="h2"><img src="/img/rpi.png" style="max-width:2em;"> Raspberry Pi Power</h2>
            </div>
            <div style="margin-top:2em;margin-bottom:4em;">
              <button type="submit" class="btn btn-warning" style="width:10em;" onClick="reboot();"><img src="/img/bootstrap-icons/bootstrap-reboot.svg" style="height:1.2em;"> Reboot</button>
              <button type="submit" class="btn btn-danger" style="width:10em;" onClick="shutdown();"><img src="/img/bootstrap-icons/power.svg" class="icon_white" style="height:1.3em;"> Shutdown</button>
            </div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h4 class="h2"><img src="/img/usb.png" style="max-width:2em;padding-right:.5em;"> USB Control</h4>
            </div>  

            <div style="margin-top:2em;margin-bottom:4em;">
              <button type="submit" class="btn btn-primary" style="width:10em;" onClick="resetUSB();"><img src="/img/bootstrap-icons/arrow-repeat.svg" class="icon_white" style="height:1.3em;"> Reset USB</button>
              <button type="submit" class="btn btn-primary" style="width:10em;" onClick="unplugUSB();"><img src="/img/bootstrap-icons/box-arrow-up.svg" class="icon_white" style="height:1.2em;"> Unplug USB</button>
            </div>
          </div>

          <div id="div_reboot_rpi_status" class="hidden">
            <h2 class="h2"><img src="/img/rpi.png" style="max-width:2em;"> Raspberry Pi Reboot</h2>
            <h4 style="margin-top:2em;">Progress</h4>
            <table>
              <tr>
                <td class="progress_row" style="padding-right:2em;">Reboot</td>
                <td><img src="/img/progress.gif" style="max-height:1.5em;"></td>
              </tr>  
            </table>
          </div>

          <div id="div_shutdown_status" class="hidden">
            <h2 class="h2"><img src="/img/rpi.png" style="max-width:2em;"> Raspberry Pi Shutdown</h2>
            <h4 style="margin-top:2em;">Progress</h4>
            <table>
              <tr>
                <td class="progress_row" style="padding-right:2em;">Shutdown</td>
                <td><p id="ui_shutdown_status"></p></td>
              </tr>  
            </table>
          </div>


          <div id="div_reset_usb_status" class="hidden">
            <h4 class="h2"><img src="/img/usb.png" style="max-width:2em;padding-right:.5em;"> USB Control</h4>
            <h4 style="margin-top:2em;">Progress</h4>
            <table>
              <tr>
                <td class="progress_row" style="padding-right:2em;">Stop/Unplug USB Storage</td>
                <td><p id="ui_stop_usb_status_1"></p></td>
              </tr>  
              <tr>
                <td class="progress_row" style="padding-right:2em;">Start USB Storage</td>
                <td><p id="ui_start_usb_status"></p></td>
              </tr> 
            </table>
          </div>

          <div id="div_unplug_usb_status" class="hidden">
            <h4 class="h2"><img src="/img/usb.png" style="max-width:2em;padding-right:.5em;"> USB Control</h4>
            <h4 style="margin-top:2em;">Progress</h4>
            <table>
              <tr>
                <td class="progress_row" style="padding-right:2em;">Stop/Unplug USB Storage</td>
                <td><p id="ui_stop_usb_status_2"></p></td>
              </tr>  
            </table>
          </div>
        </main>
      </div>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/usb_share_functions.js"></script>
    <script >
      function hideAllPanels() {
        hideElement("div_content_forms");
        hideElement("div_reboot_rpi_status");
        hideElement("div_shutdown_status");
        hideElement("div_reset_usb_status");
        hideElement("div_unplug_usb_status");
      }
      function switchPanel(elementID) {
        hideAllPanels();
        showElement(elementID);
      }
      function setStatus(ctl,val) {
        var control = ctl;
        var status = val;

        if(status == "not started") {
            document.getElementById(control).innerHTML = "";
        } 
        else if (status == "in progress") {
            document.getElementById(control).innerHTML = "<img src='/img/progress.gif' style='height:2em;'>";
        } 
        else if (status == "complete") {
            document.getElementById(control).innerHTML = "<img src='/img/bootstrap-icons/check2-circle.svg' class='icon'  style='height:2em;'>";
        }
        else 
        {
            document.getElementById(control).innerHTML = status;
        }
      }

      function reboot() {
        start_time = new Date().getTime();
        sessionStorage.setItem('RebootStart',start_time);

        switchPanel("div_reboot_rpi_status");

        let req = new XMLHttpRequest();
        req.open('GET', "/includes/util_rpi.php?a=reboot");
        req.onload = function() {
            if (req.status == 200) {
              sleep(3000);
              let getRebootStatusInterval = window.setInterval("getRebootRpiStatus();", 3000);
              sessionStorage.setItem('getRebootRpiStatus',getRebootStatusInterval); 
            }
        }
        req.send();
      }
      function shutdown() {
        start_time = new Date().getTime();
        sessionStorage.setItem('ShutdownStart',start_time);

        setStatus("ui_shutdown_status", "in progress");
        switchPanel("div_shutdown_status");

        let req = new XMLHttpRequest();
        req.open('GET', "/includes/util_rpi.php?a=shutdown");
        req.onload = function() {
            if (req.status == 200) {
              window.setInterval("getShutdownStatus();", 18000);
            }
        }
        req.send();
      }
      function getRebootRpiStatus() {
        update_rpi = document.getElementById("div_reboot_rpi_status");

        if(update_rpi.classList.contains('show')) {
          let req = new XMLHttpRequest();
          req.open('GET', "/includes/util_rpi.php?a=reboot_status", true);
          req.onload = function() {
            if (req.status == 200) {
              json = this.responseText.trim();
              obj = JSON.parse(json);
              var progress = obj.status.trim().toLowerCase();

              sessionStorage.setItem("reboot_progress", progress);


              if(progress == "stopped")
              {
                start_time = sessionStorage.getItem('RebootStart');
                curr_time = new Date().getTime();
                if((curr_time - start_time) > 15000) {
                  getRebootStatusInterval = sessionStorage.getItem("getRebootRpiStatus")
                  window.clearInterval(getRebootStatusInterval);
                  sessionStorage.removeItem("getRebootRpiStatus");

                  sessionStorage.removeItem("reboot_progress");
                  sessionStorage.removeItem('getUpdateRpiStatus');
                  sessionStorage.removeItem('RebootStart');
                  window.location.href = "/controls.php";
                }
              }
            } 
          }
          req.send();
        }
      }
      function getShutdownStatus() {
        start_time = sessionStorage.getItem('ShutdownStart');
        curr_time = new Date().getTime();
        
        if((curr_time - start_time) > 15000) {
          setStatus("ui_shutdown_status", "complete");
        }
      }


      function resetUSB() {
        switchPanel("div_reset_usb_status");
        setStatus("ui_stop_usb_status_1", "complete");
        setStatus("ui_start_usb_status", "in progress");

        let req = new XMLHttpRequest();
        req.open('GET', "/includes/util_usb_share.php?a=usb_reset");
        req.onload = function() {
            if (req.status == 200) {
              let getUSBStatusInterval = window.setInterval("getResetUSBStatus();", 1000);
              sessionStorage.setItem('getResetUSBStatusInterval',getUSBStatusInterval); 
            }
        }
        req.send();
      }
      function unplugUSB() {
        switchPanel("div_unplug_usb_status");
        setStatus("ui_stop_usb_status_2", "in progress");

        let req = new XMLHttpRequest();
        req.open('GET', "/includes/util_usb_share.php?a=usb_unplug");
        req.onload = function() {
            if (req.status == 200) {
              let getUSBStatusInterval = window.setInterval("getUnplugUSBStatus();", 1000);
              sessionStorage.setItem('getResetUSBStatusInterval',getUSBStatusInterval); 
            }
        }
        req.send();
      }

      function getResetUSBStatus() {
        update_rpi = document.getElementById("div_reset_usb_status");

        if(update_rpi.classList.contains('show')) {
          let req = new XMLHttpRequest();
          req.open('GET', "/includes/util_usb_share.php?a=usb_reset_status", true);
          req.onload = function() {
            if (req.status == 200) {
              json = this.responseText.trim();
              obj = JSON.parse(json);
              var progress = obj.status.trim().toLowerCase();

              sessionStorage.setItem("usb_reset_status", progress);

              if(progress == "stopping_usb") {
                setStatus("ui_stop_usb_status_1", "complete");
                setStatus("ui_start_usb_status", "in progress");
              } else if(progress == "starting_usb") {
                setStatus("ui_stop_usb_status_1", "complete");
                setStatus("ui_start_usb_status", "in progress");
              }
              else if(progress == "stopped")
              {

                setStatus("ui_stop_usb_status_1", "complete");
                setStatus("ui_start_usb_status", "complete");

                getUSBStatusInterval = sessionStorage.getItem("getResetUSBStatusInterval")
                window.clearInterval(getUSBStatusInterval);
                sessionStorage.removeItem("getResetUSBStatusInterval");

                sessionStorage.removeItem("usb_reset_status");
                window.location.href = "/controls.php";
              }
            } 
          }
          req.send();
        }
      }

      function getUnplugUSBStatus() {
        update_rpi = document.getElementById("div_unplug_usb_status");

        if(update_rpi.classList.contains('show')) {
          let req = new XMLHttpRequest();
          req.open('GET', "/includes/util_usb_share.php?a=usb_reset_status", true);
          req.onload = function() {
            if (req.status == 200) {
              json = this.responseText.trim();
              obj = JSON.parse(json);
              var progress = obj.status.trim().toLowerCase();

              sessionStorage.setItem("usb_reset_status", progress);

              if(progress == "stopping_usb") {
                setStatus("ui_stop_usb_status_2", "in progress");
              }
              else if(progress == "stopped")
              {

                setStatus("ui_stop_usb_status_2", "complete");

                getUSBStatusInterval = sessionStorage.getItem("getResetUSBStatusInterval")
                window.clearInterval(getUSBStatusInterval);
                sessionStorage.removeItem("getResetUSBStatusInterval");

                sessionStorage.removeItem("usb_reset_status");
                window.location.href = "/controls.php";
              }
            } 
          }
          req.send();
        }
      }

    </script>
  </body>
</html>
