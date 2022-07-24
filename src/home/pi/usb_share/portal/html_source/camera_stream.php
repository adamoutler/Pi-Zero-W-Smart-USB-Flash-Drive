<?php 
$_SESSION['pageClass'] = 'camera_stream';
$ip_array = explode(" ",shell_exec('hostname -I'));
$ip_address = $ip_array[0];
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Troy Smith">
    <meta http-equiv="refresh" content="180" >

    <title>USB Share Management Console : Camera Stream</title>

    

    <!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
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
            <h1 class="h2"><img src="/img/camera.png" style="height:1.6em;" class="grayscale"> Camera Stream</h1>
          </div>
          <div style="max-width:90%;margin-left:auto;margin-right:auto;">
            <img id="videoStream" src="" style="width:100%;">
            Note: Image reloades every 30 sec to release browser memory.
          </div>
	</main>
      </div>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery.min.js"></script>

    <script>
    window.onload = function afterWebPageLoad() { 
      reloadIMG();
      window.setInterval("reloadIMG();", 30000);
    }
      function reloadIMG() {
        var temp = "http://<?php echo $ip_address ?>:8080/?action=stream&" + new Date().getTime();
        newImage = new Image();
        newImage.src = temp;
        document.getElementById("videoStream").src = newImage.src;
      }
    </script>
  </body>
</html>
