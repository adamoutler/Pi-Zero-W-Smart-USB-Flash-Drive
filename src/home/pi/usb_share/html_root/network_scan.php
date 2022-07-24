
<?php 

$_SESSION['pageClass'] = 'network_scan';
require_once('includes/inc_rpi_host_details.php');

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

    <title>USB Share Management Console : Network Scan</title>

    

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
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2"><img src="/img/network.png" style="height:1.3em;" class="grayscale"> Network Scan Results</h1>
            <?php if($current_version_num > $local_version_num && $local_version_num >= 241) { ?>
              <a href="/upgrade.php"  class="btn btn-warning">Upgrade Availabe</a>
            <?php } ?>
          </div>

          <p id="status_messages" class="show" style="font-size:1.5em;color:#0d6efd;White-space: nowrap;">Scanning network <img src='/img/progress.gif' style='height:1.5em;'></p> 
          <div id="network_list" class="hidden">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>IP Address</th>
                  <th>MAC Address</th>
                  <th>Vendor</th>
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
    <script >
      window.onload = function afterWebPageLoad() { 
          getDeviceList(updateUIDeviceList); 
      } 

      function updateUIDeviceList(device_list) {
          hideElement("status_messages");
          showElement("network_list");

          var old_tbodyRef = document.getElementById('network_list').getElementsByTagName('tbody')[0];
          var tbodyRef = document.createElement('tbody');

          device_list.forEach(function(device) {
              if(device['IP'] != "") {
                  var newRow = tbodyRef.insertRow();

                  //add ip address
                  var newCell = newRow.insertCell();
                  var newText = document.createTextNode(device['IP']);
                  newCell.appendChild(newText);

                  //add mac address
                  newCell = newRow.insertCell();
                  newText = document.createTextNode(device['MAC Address']);
                  newCell.appendChild(newText);

                  //add vendor
                  newCell = newRow.insertCell();
                  newText = document.createTextNode(device['Vendor']);
                  newCell.appendChild(newText)
              }
          });

          old_tbodyRef.parentNode.replaceChild(tbodyRef, old_tbodyRef);
      }

      function getDeviceList(updateUIDeviceList) {
          let req = new XMLHttpRequest();
          req.open('GET', "/includes/util_rpi.php?a=network_scan");
        req.timeout = 5000;
          req.onload = function() {
              if (req.status == 200) {
                  var json = this.responseText.trim();
                  var obj = JSON.parse(json); 
                  updateUIDeviceList(obj.device_list);          
              } 
          }
          req.send();
      } 
    </script>
  </body>
</html>
