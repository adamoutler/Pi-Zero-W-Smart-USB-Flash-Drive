<?php

$data = array();

if (empty($_GET['a'])) {
    $data['error']  = "no attributes provided." ;
}
else {
    $upload_str = '/home/pi/usb_share/upload/';
    $disk_img = '/home/pi/usb_share/usb_share_disk.img';
    $request_type = strtolower($_GET['a']);

    $data['error']  = "" ;

    #
    # get usb share siize data
    # 

    if($request_type == 'disk_stats') {
        $disk_size_bytes = (float) shell_exec('stat -c %s ' . $disk_img);
        $disk_size = (float) abs($disk_size_bytes / 1024 / 1024 );
        $disk_used = (float) abs(folderSize($upload_str) / 1024 / 1024 );
        $disk_free = $disk_size - $disk_used;
        $avail_disk_space = (float) shell_exec('/home/pi/usb_share/scripts/rpi_usb_disk_mgmt.py -a');
        $max_usb_share = floor(($disk_used /1024) + $avail_disk_space - 2);
        
        $data['disk_size']  = round($disk_size,2) ;
        $data['disk_used']  = round($disk_used,2) ;
        $data['disk_free']  = round($disk_free,2) ;
        $data['disk_available'] = round(floatval($avail_disk_space),2);
        $data['max_usb_share'] = $max_usb_share;

    } else if ($request_type == 'file_list') {
        $fileList = glob($upload_str . '*');
        natcasesort($fileList);

        $arr_files = array();
        foreach($fileList as $file) {
            $path = $file;
            $name = str_replace($upload_str,"",$file);
            $size = number_format((filesize($file) / 1024 / 1024),2);
            $modified = date("F d, Y H:i:s",filemtime($file));
            array_push($arr_files,array('name' => $name, 'path' => $path, 'size' => $size,'modified' => $modified));
        }

        $data['upload_directory'] = $upload_str;
        $data['file_list'] = $arr_files;
    } else if ($request_type == 'delete_file') {
        $data['upload_directory'] = $upload_str;
        $arr_files = array();
        foreach($fileList as $file) {
            $path = $file;
            $name = str_replace($upload_str,"",$file);
            $size = number_format((filesize($file) / 1024 / 1024),2);
            $modified = date("F d, Y H:i:s",filemtime($file));
            array_push($arr_files,array('name' => $name, 'path' => $path, 'size' => $size,'modified' => $modified));
        }

        $data['file_list'] = $arr_files;

        $file_name = strtolower($_GET["file"]);

        $cmd = "sudo rm -f /home/pi/usb_share/upload/" . $file_name . ";";
    
        shell_exec($cmd);
    } else if ($request_type == 'usb_reset') {
        $cmd = "/home/pi/usb_share/scripts/rpi_usb_control.py --action 'reset';";
        shell_exec($cmd);
    } else if ($request_type == 'usb_unplug') {
        $cmd = "/home/pi/usb_share/scripts/rpi_usb_control.py --action 'unplug';";
        shell_exec($cmd);
    } else if ($request_type == 'usb_reset_status') {
        if(file_exists("/home/pi/usb_share/flags/usb_reset_status"))
        {
            $data['status'] = file_get_contents("/home/pi/usb_share/flags/usb_reset_status");
        }
        else 
        {
          $data['status'] = "stopped";
        }
    } else if ($request_type == 'upgrade') {
        $cmd = "sudo upgrade_usb_share;";
        shell_exec($cmd);
    }
}

#
# return the results as a json string
#

echo json_encode($data);



#
# funcitons
#
function folderSize ($dir)
{
    $size = 0;

    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }

    return $size;
}
?>