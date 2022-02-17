
<?php 

$_SESSION['pageClass'] = 'index'; 
require_once('includes/inc_rpi_host_details.php');
require_once('includes/inc_usb_storage_details.php');

$current_version_num = 0;
$local_version_num = 0;

try {
  $current_version = file_get_contents('https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/current_version.txt');
  $current_version_num = (float) preg_replace('/[^0-9]/', '', $current_version);
} catch (exception $e) { }

try {
  $local_version = file_get_contents('/home/pi/usb_share/flags/current_version.txt', true);
  $local_version_num = (float) preg_replace('/[^0-9]/', '', $local_version);
} catch (exception $e) { }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Troy Smith">
    <meta http-equiv="refresh" content="600" >

    <title>USB Share Management Console : Summary</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/dropzone.css" rel="stylesheet">
    <script src="/js/dropzone.js"></script>
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
            <h1 class="h2"><img src="img/rpi.png" style="height:2em;"> Raspberry Pi</h1>
            <?php if($current_version_num > $local_version_num && $local_version_num >= 241) { ?>
              <a href="/upgrade.php"  class="btn btn-warning">Upgrade Availabe</a>
            <?php } ?>
          </div>
          <table style='margin-bottom:1em;'>
          <tbody>
                <tr><td style='padding-right:2em;'><strong>Hostname: </strong></td><td><strong><?php echo $hostname; ?></strong></td></tr>
                <tr><td style='padding-right:2em;'>Bonjour Name: </td><td>http://<?php echo $bonjour_name; ?><span id="ui_windows_bonjour_install" class="hidden">&nbsp;&nbsp;(<a target="_new" href="https://support.apple.com/kb/DL999?locale=en_US">Windows Bonjour Installer</a>)</span></td></tr>
                <tr><td style='padding-right:2em;'>IP Address: </td><td><?php echo $ip_address; ?></td></tr>

                <tr><td style='padding-right:2em;vertical-align: top;'>Model: </td><td><?php echo $rpi_model; ?></td></tr>
                <tr><td style='padding-right:2em;vertical-align: top;'>OS: </td><td style='padding-bottom:1em;vertical-align: top;'><?php echo $rpi_os; ?></td></tr>
              </tbody>
            </table>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2"><img src="/img/bootstrap-icons/share.svg" style="height:1.6em;"> Network Share Paths</h1>
        </div>
            <h6>MacOS</h6>
            <ul>
              <li>The Raspberry Pi will show up in the Finder sidebar. Look for <strong><?php echo $hostname; ?></strong> in the network list.</li>
              <li>Alternatively, from the Finder menu, select Go Connect to server (Apple key + K) and type <strong>smb://<?php echo $hostname; ?></strong> as the server address.</li>
            </ul>
            </br>
            <h6>Windows</h6>
            Bring up Explorer (Windows key + E) and type one of the following into the address bar at the top. The Run dialogue also works (Windows key + R).
            <ul>
                <li>\\<?php echo $ip_address; ?>\usb</li>
                <li>\\<?php echo $bonjour_name; ?>\usb <span id="ui_windows_bonjour_install_2" class="hidden">&nbsp;&nbsp;(<a target="_new" href="https://support.apple.com/kb/DL999?locale=en_US">Windows Bonjour Installer</a>)</span></li>
            </ul>

          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2"><img src="img/usb.png" style="height:2em;"> USB Storage</h1>
          </div>

          <div class="hidden" id="div_rebuilding_usb_true">
            <table style='margin-bottom:2em;'>
              <tbody>
                <tr><td style='padding-right:2em;'><strong>USB Storage: </strong></td><td><strong>Rebuilding <img src='/img/progress.gif' style='height:2em;'></td></tr>
                <tr><td style='padding-right:2em;'>Used: </td><td></td></tr>
                <tr><td style='padding-right:2em;'>Free: </td><td></td></tr>
              </tbody>
            </table>
          </div>

          <div class="show" id="div_rebuilding_usb_false">
            <table style='margin-bottom:2em;'>
              <tbody>
                <tr><td style='padding-right:2em;'><strong>USB Storage: </strong></td><td style="text-align:right"><strong><?php echo number_format($disk_size_gb,2), " GB"; ?></strong></td></tr>
                <tr><td style='padding-right:2em;'>Used: </td><td style="text-align:right"><span id="ui_disk_used"><?php echo number_format($disk_used,2), " MB"; ?></span></td></tr>
                <tr><td style='padding-right:2em;'>Free: </td><td style="text-align:right"><span id="ui_disk_free"><?php echo number_format($disk_free,2), " MB"; ?></span></td></tr>
              </tbody>
            </table>
            <div id="div_dropzone">
              <form action="/includes/upload.php" class="dropzone" id="my-awesome-dropzone"></form>
          
              <p style="text-align:center;">** File uploads limited to 128MB with a 60sec timeout. Larger files may need to be uplaoded via the network share. **</p>
            </div>
            <div id="div_block_upload_files" class="dropzone_disabled dropzone_disabled_message hidden" >Upload disabled while Photon Mono is <span id="print_job"></span></div>
          </div>
          <div class="table-responsive">
            <table id="usb_file_list" class="table table-striped table-sm" style="margin-bottom:4em;">
              <thead>
                <tr>
                  <th>Name</th>
                  <th style="text-align:right;padding-right:2em;">Size</th>
                  <th>Modified</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/usb_share_functions.js"></script>

    <script>
      window.onload = function afterWebPageLoad() { 
        getHostDetails();
        getUSBShareDetails();
        getFileList();

        window.setInterval("getFileList();", 15000);
      } 

      function updateUIHostDetail(obj) {
        user_agent = obj.http_user_agent;
        rebuilding_usb_share = obj.rebuilding_usb_share;
        anycubic_enabled = obj.anycubic_enabled;

        if(user_agent.toLowerCase().includes("windows")) {
          showElement("ui_windows_bonjour_install");
          showElement("ui_windows_bonjour_install_2");
        }

        if(rebuilding_usb_share.toLowerCase() == "true") {
          showElement("div_rebuilding_usb_true");
          hideElement("div_rebuilding_usb_false");
        } else {
          hideElement("div_rebuilding_usb_true");
          showElement("div_rebuilding_usb_false");
          
          if(anycubic_enabled.toLowerCase() == "true") {
            getAnycubicDetails();
          } 
        }

      }
      function updateUIUSBShareDetails(obj) {
        disk_used = parseFloat(obj.disk_used).toFixed(2);
        disk_free = parseFloat(obj.disk_free).toFixed(2);

        updateElement("ui_disk_used", parseFloat(disk_used.toLocaleString("en-US")) + " MB");
        updateElement("ui_disk_free", parseFloat(disk_free).toLocaleString("en-US") + " MB");
      }
      function updateUIUFileList(obj) {
        var upload_dir = obj.upload_directory;
        var file_list = obj.file_list;
        var old_tbodyRef = document.getElementById('usb_file_list').getElementsByTagName('tbody')[0];
        var tbodyRef = document.createElement('tbody');

        for (let i = 0; i < file_list.length; i++) {
          if(!file_list[i]["name"].toLowerCase().includes("history.bin")){
            var newRow = tbodyRef.insertRow();

            //add file name
            var newCell = newRow.insertCell();
            var newText = document.createTextNode(file_list[i]["name"]);
            newCell.appendChild(newText);

            //add size
            newCell = newRow.insertCell();
            newText = document.createTextNode(file_list[i]["size"] + " MB");
            newCell.style.textAlign = "right";
            newCell.style.paddingRight = "2em";
            newCell.appendChild(newText);

            //add modified
            newCell = newRow.insertCell();
            newText = document.createTextNode(file_list[i]["modified"]);
            newCell.appendChild(newText);

            //add delete
            newCell = newRow.insertCell();
            btn = document.createElement('input');
            btn.src = '/img/trash.svg';
            btn.type  = 'image';
            btn.value = 'Delete File';
            btn.addEventListener('click', function() {
              url = "/includes/util_usb_share.php?a=delete_file&file='" + file_list[i]["name"] + "'";
              let req = new XMLHttpRequest();
              req.open('GET', url);
              req.timeout = 3000;
              req.onload = function() {
                if (req.status == 200) {
                  getUSBShareDetails();
                  getFileList();
                } 
              }
              req.send();
            }, false);
            url = encodeURI("/includes/util_usb_share.php?a=delete_file&file='" + file_list[i]["name"] + "'");
            newCell.appendChild(btn);
          }
        }
        old_tbodyRef.parentNode.replaceChild(tbodyRef, old_tbodyRef);
      }
      function updateAnycubicDetails(obj) {
        date_run = obj.date_run;
        error = obj.error;
        printer_connection = obj.printer_connection;
        printer_status = obj.printer_status.toLowerCase();
        print_job_name = obj.print_job_name;

        if(printer_status == "stopped" || printer_connection.toLowerCase() == "not connected")
        {
          hideElement("div_block_upload_files");
          showElement("div_dropzone");
        } else {
          showElement("div_block_upload_files");
          hideElement("div_dropzone");
          if(printer_status == "printing") {
            updateElement("print_job", "printing : " + print_job_name);
          } else {
            updateElement("print_job", " paused : name not availabe.");
          }
        }
      }

      function getHostDetails() {
        url = "/includes/util_rpi.php?a=host_info";
        let req = new XMLHttpRequest();
        req.open('GET', url);
        req.timeout = 3000;
        req.onload = function() {
          if (req.status == 200) {
            json = this.responseText.trim();
            obj = JSON.parse(json);
            updateUIHostDetail(obj);

            if(obj.anycubic_enabled.toLowerCase() == "true") {
              window.setInterval("getAnycubicDetails();", 5000);
            } 
          }
        }
        req.send();
      }
      function getUSBShareDetails() {
        url = "/includes/util_usb_share.php?a=disk_stats";
        let req = new XMLHttpRequest();
        req.timeout = 3000;
        req.open('GET', url);
        req.onload = function() {
          if (req.status == 200) {
            json = this.responseText.trim();
            updateUIUSBShareDetails(JSON.parse(json));
          } 
        }
        req.send();
      }
      function getFileList() {
        url = "/includes/util_usb_share.php?a=file_list";
        let req = new XMLHttpRequest();
        req.open('GET', url);
        req.timeout = 3000;
        req.onload = function() {
          if (req.status == 200) {
            var json = this.responseText.trim();
            var obj = JSON.parse(json);
            updateUIUFileList(obj);
          } 
        }
        req.send();
      }
      function getAnycubicDetails() {
        url = "/includes/util_anycubic.php?a=printer_status";
        let req = new XMLHttpRequest();
        req.open('GET', url);
        req.timeout = 3000;
        req.onload = function() {
          if (req.status == 200) {
            json = this.responseText.trim();
            updateAnycubicDetails(JSON.parse(json));
          } 
        }
        req.send();
      }

      Dropzone.options.myAwesomeDropzone = {
        maxFilesize: 128,
        maxFiles: 5,
        forceChunking: true,
        retryChunks: true,
        retryChunksLimit: 3,
        parallelChunkUploads: true,
        parallelUploads: 1,
        timeout:60000,
        init: function() {
          this.on("queuecomplete", function(file) { 
              this.removeAllFiles(); 
              getFileList();
            });
        }
      };

    </script>
  </body>
</html>
