<?php
    $upload_str = '/home/pi/usb_share/upload/';
    $disk_img = '/home/pi/usb_share/usb_share_disk.img';

    $disk_size_bytes = (float) shell_exec('stat -c %s ' . $disk_img) ;
    $disk_size = (float) round( abs($disk_size_bytes / 1024 / 1024 ),2) ;
    $disk_size_gb = (float) round( abs($disk_size_bytes / 1024 / 1024 / 1024 ),2) ;
    $disk_used = (float) round( abs(folderSize($upload_str) / 1024 / 1024 ),2);
    $disk_free = (float) round($disk_size - $disk_used,2) ; 
    $avail_disk_space = (float) shell_exec('/home/pi/usb_share/scripts/rpi_usb_disk_mgmt.py -a');
    #$max_usb_share = (float) floor($avail_disk_space - 2);
    $max_usb_share = (float) floor(($disk_used / 1024) + $avail_disk_space - 2);

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