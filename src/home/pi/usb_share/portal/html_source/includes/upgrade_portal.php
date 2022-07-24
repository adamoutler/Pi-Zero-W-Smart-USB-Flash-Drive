<?php 
$submitButton = strtolower($_POST["submitButton"]);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Troy Smith">

    <title>USB Share Management Console : Upgrade Portal</title>

    

    <!-- Bootstrap core CSS -->
<link href="/css/bootstrap.min.css" rel="stylesheet">

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
    <link href="/css/dashboard.css" rel="stylesheet">
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
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
   
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">System Rebooting</h1>
        </div>
        <h5>Upgrade in progress <img src='/img/progress.gif' style='height:2em;'></h5>
        </br>

        <script>
            window.setInterval("reloadPage();", 5000);

            function reloadPage() {
                let req = new XMLHttpRequest();
                req.open('GET', "/includes/util_usb_share.php?a=upgrade");
                req.onload = function() {
                if (req.status == 200) {
                    window.location.href = "/settings.php";
                } 
                }
                req.send();
            }
        </script>

        </main>
    </div>
    </div>
    <script src="/js/bootstrap.bundle.min.js"></script>

  </body>
</html>

