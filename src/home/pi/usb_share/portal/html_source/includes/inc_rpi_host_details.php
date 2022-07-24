<?php
    $ip_array = explode(" ",shell_exec('hostname -I')) ;
    $os_array = explode("\n", shell_exec('lsb_release -a'));

    $hostname = strtoupper(gethostname()) ;
    $bonjour_name = strtolower($hostname) . ".local";
    $ip_address = $ip_array[0] ;
    $rpi_model = nl2br(shell_exec('cat /proc/device-tree/model')) ;
    $rpi_os = str_replace('Description:','',$os_array[1]) ;
    
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    //$user_platform = str_contains(strtoupper($user_agent),"WINDOWS") ? 'windows' : 'macos' ;

    # details on running usb share processes

    $rebuilding_usb_share = file_exists('/home/pi/.usb_share_resources/portal/rebuilding_usb') ? 'true' : 'false';
    $mono_x_enabled = file_exists('/home/pi/.usb_share_resources/portal/enable_mono_x') ? 'true' : 'false';
    $camera_enabled = file_exists('/home/pi/.usb_share_resources/portal/enable_camera') ? 'true' : 'false';

    $rotate = 0;
    if(file_exists('/home/pi/.usb_share_resources/portal/rotate_90') ) { $rotate = 90; }
    else if(file_exists('/home/pi/.usb_share_resources/portal/rotate_180') ) { $rotate = 180; }
    else if(file_exists('/home/pi/.usb_share_resources/portal/rotate_270') ) { $rotate = 270; }

?>