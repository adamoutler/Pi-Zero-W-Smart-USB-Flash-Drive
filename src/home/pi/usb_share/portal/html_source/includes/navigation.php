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
        if(file_exists('/home/pi/.usb_share_resources/portal/enable_mono_x'))
        {
        ?>
          <li class="nav-item">
            <a class="nav-link <?php if($_SESSION['pageClass'] == 'mono_x' ) echo 'active'; ?>" href="/mono_x.php">
              <span data-feather="stream"></span>
              <img src="/img/PhotonMonoX.jpg" style="height:1.6em;filter: grayscale(.5);"><span style="padding-left:.2em;"> Photon Mono X</span>
            </a>
          </li> 
        <?php
        }
    ?>

    <?php 
    if(file_exists('/home/pi/.usb_share_resources/portal/enable_camera'))
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
<div style="position: absolute;bottom:0; width:100%;height:2em;padding-left: 1em;color: #888;">

<?php 
  $local_version = "0.0.0";

  try {
    $local_version = file_get_contents('/home/pi/.usb_share_resources/portal/current_version.txt', true);

    if($local_version != "")
    {
      echo "ver: ", $local_version;
    }
  } catch (exception $e) { }
  
?>
</div>