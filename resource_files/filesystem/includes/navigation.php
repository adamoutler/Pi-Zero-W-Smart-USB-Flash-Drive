<?php 
$flag_dir = '/home/pi/usb_share/flags';
$printer_model = strtolower(trim(file_get_contents("$flag_dir/printer_model")));
?>
<div class="position-sticky pt-3">
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link <?php if($_SESSION['pageClass'] == 'index' ) echo 'active'; ?>" aria-current="page" href="/index.php">
        <span data-feather="home"></span>
        <img src="/img/info.png" style="height:1.5em;"><span style="padding-left:.2em;">Summary</span>
      </a>
    </li>        
    <li class="nav-item">
      <a class="nav-link <?php if($_SESSION['pageClass'] == 'network_scan' ) echo 'active'; ?>" href="/network_scan.php">
        <span data-feather="network"></span>
        <img src="/img/network.png" style="height:1.3em;"><span style="padding-left:.3em;">Network Scan</span>
      </a>
    </li>

    <?php 
        if(file_exists("$flag_dir/enable_anycubic") == true)
        {
        ?>
          <li class="nav-item">
            <a class="nav-link <?php if($_SESSION['pageClass'] == 'anycubic' ) echo 'active'; ?>" href="/anycubic_mono.php">
              <span data-feather="stream"></span>

              <?php if (strpos($printer_model, 'mono se') !== false){ ?>
              <img src="/img/PhotonMonoSE.jpg" style="height:1.6em;filter: grayscale(.5);"><span style="padding-left:.2em;"> Photon Mono SE</span>
              <?php } elseif (strpos($printer_model, 'mono x') !== false) {?>
                <img src="/img/PhotonMonoX.jpg" style="height:1.6em;filter: grayscale(.5);"><span style="padding-left:.2em;"> Photon Mono X</span>
              <?php } else { ?>
                <img src="/img/anycubic.jpg" style="height:1.6em;filter: grayscale(.5);"><span style="padding-left:.2em;"> Photon Mono</span>
                <?php } ?>
            </a>
          </li> 
        <?php
        }
    ?>

    <?php 
    if(file_exists("$flag_dir/enable_camera") == true)
    {
      ?>
        <li class="nav-item">
          <a class="nav-link <?php if($_SESSION['pageClass'] == 'camera_stream' ) echo 'active'; ?>" href="/camera_stream.php">
            <span data-feather="stream"></span>
            <img src="/img/camera.png" style="height:1.5em;"><span style="padding-left:.2em;">Camera</span>
          </a>
        </li> 
    <?php
    }
    ?>
          
    <li class="nav-item">
      <a class="nav-link <?php if($_SESSION['pageClass'] == 'settings' ) echo 'active'; ?>" href="/settings.php">
        <span data-feather="network"></span>
        <img src="/img/gears.png" style="height:1.3em;"><span style="padding-left:.3em;">Settings</span>
      </a>
    </li> 
    <li class="nav-item">
      <a class="nav-link <?php if($_SESSION['pageClass'] == 'tools' ) echo 'active'; ?>" href="/controls.php">
        <span data-feather="network"></span>
        <img src="/img/bootstrap-icons/power.svg" style="height:1.5em;" class="icon icon_sm"><span style="padding-left:.3em;" >Power / Control</span>
      </a>
    </li> 
  </ul>
</div>
<div style="position: absolute;bottom:0; width:100%;height:7em;padding-left: 1em;color: #888;">

<?php 
  $local_version = "0.0.0";

  try {
    $local_version = file_get_contents("$flag_dir/current_version.txt", true);

    if($local_version != "")
    {
      echo "<div>ver: <a href='/upgrade.php'>", $local_version, "</a></div>";
    }
  } catch (exception $e) { }
  
?>
  <div style="margin-top:.75em;">
    <a target="_github" href="https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive" style="color: #007bff;font-size:.9em;text-decoration:none;"><img src="/img/github.png" style="height:1.2em;"> GitHub Project</a>
    <br>
    <a target="_donation" href="https://www.buymeacoffee.com/tds2021" style="color: #007bff;font-size:.9em;text-decoration:none;"><img src="/img/books.png" style="height:1.2em;"> Buy me a book</a>
  </div>
</div>