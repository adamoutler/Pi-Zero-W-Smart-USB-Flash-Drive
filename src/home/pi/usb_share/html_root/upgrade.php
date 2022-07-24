<?php 
$_SESSION['pageClass'] = 'settings'; 
require_once('includes/inc_rpi_host_details.php');
require_once('includes/inc_usb_storage_details.php');

$current_version_num = 0;
$local_version_num = 0;
$upgrade_details = "";
$version_history = "";

try {
  $current_version = file_get_contents('https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/current_version.txt');
  $current_version_num = (float) preg_replace('/[^0-9]/', '', $current_version);
} catch (exception $e) { }

try {
    $upgrade_details = file_get_contents('https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/upgrade_details.html');
  } catch (exception $e) { }

try {
    $version_history = file_get_contents('https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/version_history.html');
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

    <title>USB Share Management Console : Upgrade</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

    <style>
      input[type=text] {
        max-width: 200px;
      }
      progress_row {
        min-height:2.5em;
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
                <h1 class="h2"><img src="/img/bootstrap-icons/sliders.svg" style="height:1.2em;"> Upgrade Details</h1>
            </div>

            <?php echo $upgrade_details; ?>

            <?php echo $version_history; ?>

        </main>
      </div>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/usb_share_functions.js"></script>
  </body>
</html>
