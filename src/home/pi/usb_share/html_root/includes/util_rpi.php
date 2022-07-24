<?php
$data = array();
$data['date_run']  = date("Y-m-d H:i:s",time()) ;

if (empty($_GET['a'])) {
    $data['error']  = "no attributes provided." ;
}
else {
    $request_type = strtolower($_GET['a']) ;

    $data['error']  = "" ;

    #
    # get usb share siize data
    # 

    if($request_type == 'host_info') {

        $ip_array = explode(" ",shell_exec('hostname -I')) ;
        $ip_address = $ip_array[0] ;
        
        $data['hostname'] = strtoupper(gethostname()) ;
        $data['ip_address']  = $ip_address ;

        $data['rpi_model'] = nl2br(shell_exec('cat /proc/device-tree/model'));

        $os_array = explode("\n", shell_exec('lsb_release -a'));
        $data['rpi_os'] = str_replace('Description:','',$os_array[1]);

        $data['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        # details on running usb share processes

        $data['rebuilding_usb_share'] = file_exists('/home/pi/usb_share/flags/rebuilding_usb') ? 'true' : 'false';
        $data['anycubic_enabled'] = file_exists('/home/pi/usb_share/flags/enable_anycubic') ? 'true' : 'false';
        $data['camera_enabled'] = file_exists('/home/pi/usb_share/flags/enable_camera') ? 'true' : 'false';

        $rotate = 0;
        if(file_exists('/home/pi/usb_share/flags/rotate_90') ) { $rotate = 90; }
        else if(file_exists('/home/pi/usb_share/flags/rotate_180') ) { $rotate = 180; }
        else if(file_exists('/home/pi/usb_share/flags/rotate_270') ) { $rotate = 270; }
        $data['camera_rotation'] = $rotate;

    } else if ($request_type == 'network_scan') {
        $ip_array = explode(" ",shell_exec('hostname -I')) ;
        $ip_address = $ip_array[0] ;
        $mac_address = shell_exec('cat /sys/class/net/wlan0/address ');

        $array_list = explode("\n", shell_exec('sudo arp-scan -I wlan0 -l'));
        $list_length = count($array_list);

        for($i = 2;$i < ($list_length - 3); $i++) {
          $array_device = explode("\t",$array_list[$i]);
          $ip_array = explode(".",$array_device[0]);

          if (strpos($array_device[2], '(DUP:') === false) {
            $device_arrary[] = array('IP' => $array_device[0], 'MAC Address' => $array_device[1], 'Vendor' => str_replace("(Unknown)","",$array_device[2]),'IP_BLOCK_1' => $ip_array [0],'IP_BLOCK_2' => $ip_array [1],'IP_BLOCK_3' => $ip_array [2],'IP_BLOCK_4' => $ip_array [3]);
          }    
        }
        $ip_array = explode(".",$ip_address);
        $device_arrary[] = array('IP' => $ip_address, 'MAC Address' => $mac_address , 'Vendor' => "Raspberry Pi Foundation",'IP_BLOCK_1' => $ip_array [0],'IP_BLOCK_2' => $ip_array [1],'IP_BLOCK_3' => $ip_array [2],'IP_BLOCK_4' => $ip_array [3]);
        
        $columns = array_column($device_arrary, 'IP_BLOCK_4');
        array_multisort($columns, SORT_ASC, $device_arrary);
        $data['device_list'] = $device_arrary;

    } else if ($request_type == 'update') {
      $current_hostname = strtolower($_GET["current_hostname"]);
      $new_hostname = strtolower($_GET["new_hostname"]);
      $enable_camera = strtolower($_GET["enable_camera"]);
      $rotation = strtolower($_GET["rotation"]);
      $cmd = "sudo /home/pi/usb_share/scripts/rpi_update.py --current_hostname " . $current_hostname . " --new_hostname " . $new_hostname . " --enable_camera " . $enable_camera ." --rotation " . $rotation . " &";
      shell_exec($cmd);
    } else if ($request_type == 'update_status') {
      if(file_exists("/home/pi/usb_share/flags/updating_rpi"))
      {
          $data['status'] = file_get_contents("/home/pi/usb_share/flags/updating_rpi");
      }
      else 
      {
        $data['status'] = "stopped";
      }
    } else if ($request_type == 'rebuild_usb') {
      $new_usb_size = $_GET["usb_size"];
      $cmd = 'sudo /home/pi/usb_share/scripts/rpi_usb_disk_create.sh ' . $new_usb_size . '&';
      shell_exec($cmd);
    } else if ($request_type == 'rebuild_status') {
      if(file_exists("/home/pi/usb_share/flags/rebuilding_usb"))
      {
          $data['status'] = file_get_contents("/home/pi/usb_share/flags/rebuilding_usb");
      }
      else 
      {
        $data['status'] = "stopped";
      }
    } else if ($request_type == 'get_disk_size') {
      $current_usb_size = shell_exec('/home/pi/usb_share/scripts/rpi_usb_disk_mgmt.py -umb');
      $data['disk_size'] = $current_usb_size;
    } else if ($request_type == 'rebuild_reboot') {
      $cmd = 'sudo /home/pi/usb_share/scripts/rpi_usb_disk_create.sh reboot';
      shell_exec($cmd); 
    } else if ($request_type == 'reboot') {
      $cmd = "sudo /home/pi/usb_share/scripts/rpi_update.py --reboot yes;";
      shell_exec($cmd); 
    } else if ($request_type == 'shutdown') {
      $cmd = "sudo /home/pi/usb_share/scripts/rpi_update.py --shutdown yes;";
      shell_exec($cmd); 
    } else if ($request_type == 'reboot_status') {
      if(file_exists("/home/pi/usb_share/flags/updating_rpi"))
      {
          $data['status'] = file_get_contents("/home/pi/usb_share/flags/updating_rpi");
      }
      else 
      {
        $data['status'] = "stopped";
      }
    }
    
}

#
# return the results as a json string
#
    
echo json_encode($data);


?>