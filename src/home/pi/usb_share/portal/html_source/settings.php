<?php 
$_SESSION['pageClass'] = 'settings'; 
require_once('includes/inc_rpi_host_details.php');
require_once('includes/inc_usb_storage_details.php');

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Troy Smith">

    <title>USB Share Management Console : Settings</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

    <style>
      input[type=text] {
        max-width: 200px;
      }
    </style>
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
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2"><img src="/img/bootstrap-icons/sliders.svg" style="height:1.2em;"> Settings</h1>
            <?php 
            $current_version = "";
            $local_version = "0.0.0";

            try {
              $current_version = file_get_contents('https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/current_version.txt');
            } catch (exception $e) { }

            try {
              $local_version = file_get_contents('/home/pi/.usb_share_resources/portal/current_version.txt', true);
            } catch (exception $e) { }
            
            if(trim($current_version) != trim($local_version))
            {
            ?>
              <form class = "form-inline" role="form" method="post" enctype="multipart/form-data" action="/includes/upgrade_portal.php">
                <button type="submit" class="btn btn-warning">Upgrade to Version: <?php echo $current_version; ?></button>
              </form>
            <?php
            } 
            ?>
          </div>
          <div id="content_settings_forms" class="show">
            <h4 style="border-bottom:1px solid #ccc;padding-bottom:.25em;margin-bottom:.75em;max-width:400px"><img src="img/rpi.png" style="height:1.5em;"> Raspberry Pi</h4>
            <div id="div_rpi_update_form" class="show" style="padding-bottom:4em;">
              <table>
                <tr>
                  <td style="width:8em;"><label class = "sr-only" for = "name"><span style="font-size:1.5em;">Hostname: </span></label></td>
                  <td>
                    <input type = "hidden" id = "current_hostname" name= "current_hostname" value="<?php echo $hostname; ?>">
                    <input type = "text" id = "new_hostname" name= "new_hostname"  class = "form-control" required="true" value="<?php echo $hostname; ?>">
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <div class="checkbox" style="margin-top:1em;">
                        <label><input type="checkbox" id="enable_camera" name="enable_camera"> Enable Camera Support</label>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="padding-left:2em;"> 
                    <table>
                      <tr>
                        <td>Rotate: </td>
                        <td>
                          <select name="rotation" id="rotation">
                            <option value="0">No Rotation</option>
                            <option value="90">90&deg;</option>
                            <option value="180">180&deg;</option>
                            <option value="270">270&deg;</option>
                          </select>
                        </td>
                      </tr>
                    </table>
                  </td>
                <tr>
                <td style="padding-top:1em;vertical-align:middle;"><button id="btn_rpi_update" type="submit" class="btn btn-primary" onclick="updateRpi();" >Update</button></td>
                <td colspan="2" style="padding-top:1em;vertical-align:middle;"> Note: The system will reboot<br>for changes to take effect.</td>
                </tr>
              </table>
            </div>

            <h4 style="border-bottom:1px solid #ccc;padding-bottom:.25em;margin-bottom:.75em;max-width:400px"><img src="img/usb.png" style="height:1.5em;"> USB Storage</h4>
            <div id="div_usb_rebuilding" class="hidden" style="padding-bottom:4em;">
              <table>
                <tr><td>SD Card Free Space:</td><td>Rebuilding <img src='/img/progress.gif' style='height:2em;'></td></tr>
                <tr><td>Current USB Storage:</td><td></td></tr>
                <tr><td style="padding:1em 0 1em 0;padding-right:1em;">Adjusted USB Storage:</td><td style="padding:1em 0 1em 0;"></td></tr>
              </table>
            </div>
            <div id="div_usb_config" class="show" style="padding-bottom:4em;">
              <table>
                <tr><td>SD Card Free Space:</td><td><?php echo $avail_disk_space, " GB"; ?></td></tr>
                <tr><td>Current USB Storage:</td><td><span id="ui_disk_size"><?php echo number_format($disk_size_gb,2), " GB"; ?></span></td></tr>
                <tr><td style="padding:1em 0 1em 0;padding-right:1em;">Adjusted USB Storage:</td><td style="padding:1em 0 1em 0;">
                  <select name="new_usb_size" id="new_usb_size">
                  </select>
                  </td></tr>
                <tr><td colspan="2"><button type="submit" class="btn btn-primary" onClick="rebuildUSB();">Rebuild USB Storage</button></td></tr>
              </table>
            </div>
            <div id="div_enabled_mono_x" class="hidden">
              <h4 style="border-bottom:1px solid #ccc;padding-bottom:.25em;margin-bottom:.75em;max-width:400px"><img src="img/PhotonMonoX.jpg" style="max-width:1.75em;filter: grayscale(.5);"> ANYCUBIC Mono X Support</h4>
              <table>
                <tr>
                  <td style="width:8em;"><label class = "sr-only" for = "printer_ip"><span style="font-size:1.5em;">Printer IP: </span></label></td>
                  <td>
                    <input type = "text" id = "printer_ip" name= "printer_ip"  placeholder = "###.###.###.###" class = "form-control">
                  </td>
                </tr>
                <tr>
                  <td colspan='2'>
                  <div class="checkbox" style="margin-top:1em;">
                    <label><input type="checkbox" id="enable_print_protection" name="enable_print_protection" checked=true disabled=true > Enable Print Protection</label>
                  </div>

                  <div class="checkbox" style="margin-top:1em;">
                    <label><input type="checkbox" id="enable_wifi_file"  name="enable_wifi_file"> Enable WiFi.txt file creation</label>
                  </div>
                  </td>
                </tr>
                <tr>
                  <td colspan='2' style="padding-top:1em;">
                  <button id="btn_mono_x_update" type="submit" class="btn btn-primary" onclick="updateMonoX();">Update</button>
                  <button id="btn_mono_x_disable" type="submit" class="btn btn-warning" onclick="disableMonoX();">Disable Mono X Support</button>
                  </td>
                </tr>
                <tr>
                  <td colspan='2' style="padding-top:1em;">
                      <span id="mono_x_update_status" class="hidden" style="color:green">Updated.</span>
                  </td>
                </tr>
              </table>

              
            </div>
            <div id="div_disabled_mono_x" class="hidden">
              <h4 style="border-bottom:1px solid #ccc;padding-bottom:.25em;margin-bottom:.75em;max-width:400px"><img src="img/PhotonMonoX.jpg" style="max-width:1.75em;filter: grayscale(.5);"> ANYCUBIC Mono X Support</h4>
              <button type="submit" class="btn btn-primary" onclick="enableMonoX();">Enable Mono X Support</button>
            </div>
          </div>

          <div id="div_rip_update_status" class="hidden">
            <h4 style="border-bottom:1px solid #ccc;padding-bottom:.25em;margin-bottom:.75em;max-width:400px"><img src="img/rpi.png" style="height:1.5em;"> Raspberry Pi Updating</h4>
            <table>
                <tr>
                    <td>Hostname:</td>
                    <td><span id="ui_new_hostname"></span></td>
                </tr>
                <tr>
                    <td style="padding-right:2em;">Enable Camera:</td>
                    <td><span id="ui_enable_camera"></span></td>
                </tr>
                <tr>
                    <td>Rotate:</td>
                    <td><span id="ui_camera_rotation"></span></td>
                </tr>
            </table>
            <h4 style="margin-top:2em;">Progress</h4>
            <table>
                <tr>
                    <td class="progress_row">Update camera configuration</td>
                    <td><p id="ui_rpi_update_camera_status"></p></td>
                </tr>
                <tr>
                    <td class="progress_row">Update hostname</td>
                    <td><p id="ui_rpi_update_hostname_status"></p></td>
                </tr> 
                <tr>
                    <td class="progress_row">Reboot</td>
                    <td><p id="ui_rpi_update_reboot_status"></p></td>
                </tr>    
            </table>
          </div>

          <div id="div_rebuild_usb_status" class="hidden">
            <h4 style="border-bottom:1px solid #ccc;padding-bottom:.25em;margin-bottom:.75em;max-width:400px"><img src="img/usb.png" style="height:1.5em;"> Rebuild USB Storage</h4>
            <table>
                <tr>
                    <td>SD Card Free Space:</td>
                    <td><?php echo $avail_disk_space, " GB"; ?></td>
                </tr>
                <tr>
                    <td>Current USB Storage:</td>
                    <td><span id="ui_disk_size_2"></span></td>
                </tr>
                
                <tr>
                    <td style="padding-right:1em;">Adjusted USB Storage:</td>
                    <td><span id="ui_disk_new_size"></span></td>
                </tr>
            </table>
            <h4 style="margin-top:2em;">Progress</h4>
            <table>
                <tr>
                    <td class="progress_row">Stop services</td>
                    <td ><p id="progress_stop_services"></p></td>
                </tr>
                <tr>
                    <td class="progress_row">Delete existing USB storage</td>
                    <td ><p id="progress_delete_usb"></p></td>
                </tr> 
                <tr>
                    <td class="progress_row" style="padding-right:2em;">Create New USB Storage<br><span style="color:#ccc;">Estimated duration: </span><span id="ui_estimated_duration" style="color:#ccc;"></span></td>
                    <td><p id="progress_create_usb"></p><p id="prcnt_complete" style="font-size:1.5em;"></p></td>
                </tr> 
                <tr>
                    <td class="progress_row">Create file system</td>
                    <td><p id="progress_create_fs"></p></td>
                </tr> 
                <tr>
                    <td class="progress_row">Start services</td>
                    <td><p id="progress_start_services"></p></td>
                </tr>  
                <tr>
                    <td class="progress_row">Reboot</td>
                    <td><p id="progress_reboot"></p></td>
                </tr>    
            </table>
          </div>

          <div id="div_mono_x_update_status" class="hidden">
            <h4 style="border-bottom:1px solid #ccc;padding-bottom:.25em;margin-bottom:.75em;max-width:400px"><img src="img/PhotonMonoX.jpg" style="max-width:1.75em;filter: grayscale(.5);"> ANYCUBIC Mono X Support</h4>
            <table>
              <tr>
                  <td style="padding-right:2em;">Printer IP:</td>
                  <td><span id="ui_printer_ip"></span></td>
              </tr>
              <tr>
                  <td style="padding-right:2em;">Enable Print Protection:</td>
                  <td><span id="ui_enable_printer_protection"></span></td>
              </tr>
              <tr>
                  <td style="padding-right:2em;">Enable WiFi.txt file creation:</td>
                  <td><span id="ui_enable_wifi_file"></span></td>
              </tr>
            </table>
            <h4 style="margin-top:2em;">Progress</h4>
            <table>
                <tr>
                    <td class="progress_row" style="padding-right:2em;">Updating Mono X Config</td>
                    <td><img src="/img/progress.gif" style="max-height:1.5em;"></td>
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
      window.onload = function afterWebPageLoad() { 
        getHostDetails();
        getUSBShareDetails();
        showElement("content_settings_forms");
      } 

      function hideAllPanels() {
        hideElement("content_settings_forms");
        hideElement("div_rip_update_status");
        hideElement("div_rebuild_usb_status");
        hideElement("div_mono_x_update_status");
      }
      function switchPanel(elementID) {
        hideAllPanels();
        showElement(elementID);
      }
      function updateUIUSBShareDetails(obj) {
        max_usb_share = <?php echo $max_usb_share; ?>;
        disk_size = <?php echo ($disk_size / 1024); ?>;

        select = document.getElementById('new_usb_size');
        
        for(i = 1; i <= max_usb_share; i++) {
          var opt = document.createElement('option');
          opt.value = i;
          opt.innerHTML = i + " GB";
          if(i == parseInt(disk_size))
          {
            opt.selected = true;
          }
          select.appendChild(opt);
        }
      }
      function updateUIHostDetail(obj) {
        rebuilding_usb_share = obj.rebuilding_usb_share;
        mono_x_enabled = obj.mono_x_enabled;
        camera_enabled = obj.camera_enabled;
        camera_rotation = obj.camera_rotation;

        if(camera_enabled =="true") {
          document.getElementById('enable_camera').checked = true;

          if(camera_rotation == 0) { document.getElementById('rotation').children[0].selected = true; }
          else if(camera_rotation == 90) { document.getElementById('rotation').children[1].selected = true; }
          else if(camera_rotation == 180) { document.getElementById('rotation').children[2].selected = true; }
          else if(camera_rotation == 270) { document.getElementById('rotation').children[3].selected = true; }
      
        }
        getRebuildStatus();
        if(rebuilding_usb_share.toLowerCase() == "true") {
          switchPanel("div_rebuild_usb_status");

        } else {
          switchPanel("content_settings_forms");
        }

        hideElement("mono_x_update_status");

        if(mono_x_enabled.toLowerCase() == "true") {
          hideAllPanels();
          showElement("div_enabled_mono_x");
          getMonoXDetails();

        } else {
          hideAllPanels();
          showElement("div_disabled_mono_x");
        }

      }
      function updateMonoXDetails(obj) {
        date_run = obj.date_run;
        error = obj.error;
        printer_connection = obj.printer_connection;
        printer_status = obj.printer_status.toLowerCase();
        print_job_name = obj.print_job_name;

        document.getElementById('printer_ip').value = obj.printer_ip_address;

        if(obj.wifi_enabled.toLowerCase() == 'true') {
          document.getElementById('enable_wifi_file').checked = true;
        } else {
          document.getElementById('enable_wifi_file').checked = false;
        }
        
      }
      function setRebuildStatus(ctl,val) {
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
      function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
      }


      async function getHostDetails() {
        url = "/includes/util_rpi.php?a=host_info";
        let req = new XMLHttpRequest();
        req.open('GET', url, false);
        req.onload = function() {
          if (req.status == 200) {
            json = this.responseText.trim();
            obj = JSON.parse(json);
            updateUIHostDetail(obj);
          }
        }
        req.send();
      }
      async function getMonoXDetails() {
        url = "/includes/util_mono_x.php?a=printer_status";
        let req = new XMLHttpRequest();
        req.open('GET', url, false);
        req.onload = function() {
          if (req.status == 200) {
            json = this.responseText.trim();
            updateMonoXDetails(JSON.parse(json));
          } 
        }
        req.send();
      }
      async function getUSBShareDetails() {
        url = "/includes/util_usb_share.php?a=disk_stats";
        let req = new XMLHttpRequest();
        req.open('GET', url, false);
        req.onload = function() {
          if (req.status == 200) {
            json = this.responseText.trim();
            updateUIUSBShareDetails(JSON.parse(json));
          } 
        }
        req.send();
      }



      async function enableMonoX() {
        url = "/includes/util_mono_x.php?a=enable_mono_x"
        let req = new XMLHttpRequest();
        req.open('GET', url, false);
        req.onload = function() {
          if (req.status == 200) {
            showElement("div_enabled_mono_x");
            hideElement("div_disabled_mono_x");
            //window.location.href = "/settings.php";
          } 
        }
        req.send();

        showElement("content_settings_forms");
      }
      async function disableMonoX() {
        url = "/includes/util_mono_x.php?a=disable_mono_x"
        let req = new XMLHttpRequest();
        req.open('GET', url, false);
        req.onload = function() {
          if (req.status == 200) {
            hideElement("div_enabled_mono_x");
            showElement("div_disabled_mono_x");

            window.location.href = "/settings.php";
          } 
        }
        req.send();

        showElement("content_settings_forms");
      }
      async function updateMonoX() {
        printer_ip = document.getElementById('printer_ip').value;
        enable_wifi_file = document.getElementById('enable_wifi_file').checked ? 'on' : 'off';
        enable_print_protection = document.getElementById('enable_print_protection').checked ? 'on' : 'off';

        updateElement("ui_printer_ip",printer_ip);
        updateElement("ui_enable_printer_protection",(enable_print_protection == 'on') ? 'Yes' : 'No');
        updateElement("ui_enable_wifi_file",(enable_wifi_file == 'on') ? 'Yes' : 'No');

        if(printer_ip != "") {
          switchPanel("div_mono_x_update_status");

          url = "/includes/util_mono_x.php?a=update&printer_ip='" + printer_ip + "'&enable_wifi_file='" + enable_wifi_file + "'&enable_print_protection='" + enable_print_protection + "'";

          let req = new XMLHttpRequest();
          req.open('GET', url, true);
          req.onload = function() {
            if (req.status == 200) {
              json = this.responseText.trim();
              obj = JSON.parse(json);

              window.location.href = "/settings.php";
            } 
          }
          req.send();
        }

        
      }



      async function updateRpi() {
        start_time = new Date().getTime();
        sessionStorage.setItem('UpdateRpiStart',start_time);

        getHostDetailsInterval = sessionStorage.getItem("getHostDetailsInterval")
        window.clearInterval(getHostDetailsInterval);
        sessionStorage.removeItem("getHostDetailsInterval");

        current_hostname = document.getElementById('current_hostname').value;
        new_hostname = document.getElementById('new_hostname').value;
        enable_camera = document.getElementById('enable_camera').checked ? 'on' : 'off';
        rotation = document.getElementById('rotation').value;

        updateElement("ui_new_hostname",new_hostname);
        updateElement("ui_enable_camera",(enable_camera == 'on') ? 'Yes' : 'No');
        updateElement("ui_camera_rotation",(rotation == 0) ? 'No Rotation' : rotation + '&deg;');

        setRebuildStatus("ui_rpi_update_camera_status", "not started");
        setRebuildStatus("ui_rpi_update_hostname_status", "not started");
        setRebuildStatus("ui_rpi_update_reboot_status", "not started");

        switchPanel("div_rip_update_status");

        url = "/includes/util_rpi.php?a=update&current_hostname='" + current_hostname + "'&new_hostname='" + new_hostname + "'&enable_camera='" + enable_camera + "'&rotation=" + rotation;

        let req = new XMLHttpRequest();
        req.open('GET', url, true);
        req.onload = function() {
          if (req.status == 200) {
            json = this.responseText.trim();
            obj = JSON.parse(json);
            let getUpdateRpiStatusInterval = window.setInterval("getUpdateRpiStatus();", 3000);
            sessionStorage.setItem('getUpdateRpiStatusInterval',getUpdateRpiStatusInterval);
            getUpdateRpiStatus();
          } 
        }
        req.send();
      }
      function getUpdateRpiStatus() {
        update_rpi = document.getElementById("div_rip_update_status");

        if(update_rpi.classList.contains('show')) {
          let req = new XMLHttpRequest();
          req.open('GET', "/includes/util_rpi.php?a=update_status", true);
          req.onload = function() {
            if (req.status == 200) {
              json = this.responseText.trim();
              obj = JSON.parse(json);
              var progress = obj.status.trim().toLowerCase();

              sessionStorage.setItem("rpi_update_progress", progress);

              if(progress == "updating_camera")
              {
                setRebuildStatus("ui_rpi_update_camera_status", "in progress");
                setRebuildStatus("ui_rpi_update_hostname_status", "not started");
                setRebuildStatus("ui_rpi_update_reboot_status", "not started");
              }
              else if(progress == "updating_hostname")
              {
                setRebuildStatus("ui_rpi_update_camera_status", "complete");
                setRebuildStatus("ui_rpi_update_hostname_status", "in progress");
                setRebuildStatus("ui_rpi_update_reboot_status", "not started");
              }
              else if(progress == "reboot")
              {
                setRebuildStatus("ui_rpi_update_camera_status", "complete");
                setRebuildStatus("ui_rpi_update_hostname_status", "complete");
                setRebuildStatus("ui_rpi_update_reboot_status", "in progress");
                
                  let req2 = new XMLHttpRequest();
                  req2.open('GET', "/includes/util_rpi.php?a=reboot");
                  req2.onload = function() {
                    if (req2.status == 200) {
                    }
                  }
                  req2.send();
              }
              else if(progress == "complete")
              {
                setRebuildStatus("ui_rpi_update_camera_status", "complete");
                setRebuildStatus("ui_rpi_update_hostname_status", "complete");
                setRebuildStatus("ui_rpi_update_reboot_status", "complete");
              }
              else if(progress == "stopped")
              {
                start_time = sessionStorage.getItem('UpdateRpiStart');
                curr_time = new Date().getTime();

                if((curr_time - start_time) > 10000) {
                  getHostDetailsInterval = sessionStorage.getItem("getUpdateRpiStatusInterval")
                  window.clearInterval(getHostDetailsInterval);
                  sessionStorage.removeItem("getUpdateRpiStatusInterval");

                  sessionStorage.removeItem("rpi_update_progress");
                  sessionStorage.removeItem('getUpdateRpiStatus');
                  sessionStorage.removeItem('UpdateRpiStart')
                  window.location.href = "/settings.php";
                }
              }
            } 
          }
          req.send();
        }
      }



      async function rebuildUSB() {
        start_time = new Date().getTime();
        sessionStorage.setItem('RebuildUSBStart',start_time);

        let getRebuildStatusInterval = window.setInterval("getRebuildStatus();", 3000);
        sessionStorage.setItem('getRebuildStatusInterval',getRebuildStatusInterval);

        getHostDetailsInterval = sessionStorage.getItem("getHostDetailsInterval")
        window.clearInterval(getHostDetailsInterval);
        sessionStorage.removeItem("getHostDetailsInterval");

        new_disk_size = document.getElementById('new_usb_size').value;
        sessionStorage.setItem("usb_rebuild_size",new_disk_size);

        switchPanel("div_rebuild_usb_status");


        setRebuildStatus("progress_stop_services", "in progress");
        setRebuildStatus("progress_delete_usb", "not started");
        setRebuildStatus("progress_create_usb", "not started");
        setRebuildStatus("prcnt_complete", "");
        setRebuildStatus("progress_create_fs", "not started");
        setRebuildStatus("progress_start_services", "not started");
        setRebuildStatus("progress_reboot", "not started");

        updateElement("ui_disk_new_size", new_disk_size + " GB");

        date = new Date(null);
        seconds = new_disk_size * 130;
        date.setSeconds(seconds); 
        formated_date = date.toISOString().substr(11, 8);
        updateElement("ui_estimated_duration",formated_date);

        url = "/includes/util_rpi.php?a=rebuild_usb&usb_size=" + new_disk_size;

        let req = new XMLHttpRequest();
        req.open('GET', url, true);
        req.onload = function() {
          if (req.status == 200) {
            json = this.responseText.trim();
            obj = JSON.parse(json);
            sleep(3000);
            getRebuildStatus();
          } 
        }
        req.send();
        
      }
      function getRebuildStatus() {
        rebuild_usb = document.getElementById("div_rebuild_usb_status");

        if(rebuild_usb.classList.contains('show')) {
          new_img_size = (sessionStorage.getItem("usb_rebuild_size") * 1024);


          let req = new XMLHttpRequest();
          req.open('GET', "/includes/util_rpi.php?a=rebuild_status", true);
          req.onload = function() {
            if (req.status == 200) {
              json = this.responseText.trim();
              obj = JSON.parse(json);
              var progress = obj.status.trim().toLowerCase();

              sessionStorage.setItem("usb_rebuild_progress", progress);

              if(progress == "stop_services")
              {
                  setRebuildStatus("progress_stop_services", "in progress");
                  setRebuildStatus("progress_delete_usb", "not started");
                  setRebuildStatus("progress_create_usb", "not started");
                  setRebuildStatus("prcnt_complete", "");
                  setRebuildStatus("progress_create_fs", "not started");
                  setRebuildStatus("progress_start_services", "not started");
                  setRebuildStatus("progress_reboot", "not started");
              }
              else if(progress == "delete_image")
              {
                  setRebuildStatus("progress_stop_services", "complete");
                  setRebuildStatus("progress_delete_usb", "in progress");
                  setRebuildStatus("progress_create_usb", "not started");
                  setRebuildStatus("prcnt_complete", "");
                  setRebuildStatus("progress_create_fs", "not started");
                  setRebuildStatus("progress_start_services", "not started");
                  setRebuildStatus("progress_reboot", "not started");
              }
              else if(progress == "create_image")
              {
                  setRebuildStatus("progress_stop_services", "complete");
                  setRebuildStatus("progress_delete_usb", "complete");
                  setRebuildStatus("progress_create_usb", "not started");
                  setRebuildStatus("progress_create_fs", "not started");
                  setRebuildStatus("progress_start_services", "not started");
                  setRebuildStatus("progress_reboot", "not started");

                  current_size = 0;
                  percent_copmplete = 0;
                  let req2 = new XMLHttpRequest();
                  url = "/includes/util_rpi.php?a=get_disk_size";

                  req2.open('GET', url);
                  req2.onload = function() {
                      if (req2.status == 200) {
                          json = this.responseText.trim();
                          obj = JSON.parse(json);
                          current_size = obj.disk_size;
                          percent_copmplete = Math.floor((current_size / new_img_size) * 100);
                          setRebuildStatus("prcnt_complete", percent_copmplete+"%");
                      }
                  }
                  req2.send();
              }
              else if(progress == "create_fs")
              {
                  setRebuildStatus("progress_stop_services", "complete");
                  setRebuildStatus("progress_delete_usb", "complete");
                  setRebuildStatus("progress_create_usb", "complete");
                  setRebuildStatus("prcnt_complete", "");
                  setRebuildStatus("progress_create_fs", "in progress");
                  setRebuildStatus("progress_start_services", "not started");
                  setRebuildStatus("progress_reboot", "not started");
              }
              else if(progress == "start_services")
              {
                  setRebuildStatus("progress_stop_services", "complete");
                  setRebuildStatus("progress_delete_usb", "complete");
                  setRebuildStatus("progress_create_usb", "complete");
                  setRebuildStatus("prcnt_complete", "");
                  setRebuildStatus("progress_create_fs", "complete");
                  setRebuildStatus("progress_start_services", "in progress");
                  setRebuildStatus("progress_reboot", "not started");
              }
              else if(progress == "reboot")
              {
                  setRebuildStatus("progress_stop_services", "complete");
                  setRebuildStatus("progress_delete_usb", "complete");
                  setRebuildStatus("progress_create_usb", "complete");
                  setRebuildStatus("prcnt_complete", "");
                  setRebuildStatus("progress_create_fs", "complete");
                  setRebuildStatus("progress_start_services", "complete");
                  setRebuildStatus("progress_reboot", "in progress");
                  let req2 = new XMLHttpRequest();
                  req2.open('GET', "/includes/util_rpi.php?a=rebuild_reboot");
                  req2.onload = function() {
                      if (req2.status == 200) {
                      }
                  }
                  req2.send();
              }
              else if(progress == "complete")
              {
                  setRebuildStatus("progress_stop_services", "complete");
                  setRebuildStatus("progress_delete_usb", "complete");
                  setRebuildStatus("progress_create_usb", "complete");
                  setRebuildStatus("prcnt_complete", "");
                  setRebuildStatus("progress_create_fs", "complete");
                  setRebuildStatus("progress_start_services", "complete");
                  setRebuildStatus("progress_reboot", "complete");
              }
              else if(progress == "stopped")
              {
                start_time = sessionStorage.getItem('RebuildUSBStart');
                curr_time = new Date().getTime();

                if((curr_time - start_time) > 30000) {
                  sessionStorage.removeItem("usb_rebuild_size");
                  sessionStorage.removeItem("usb_rebuild_progress");
                  sessionStorage.removeItem('getRebuildStatusInterval');
                  sessionStorage.removeItem('RebuildUSBStart');
                  window.location.href = "/settings.php";
                }
              }
            } 
          }
          req.send();
        }
      }
    </script>
  </body>
</html>
