<?php 
    $printer_dir = '/home/pi/.usb_share_resources/portal';
    $exec_dir = "/var/www/html/includes/scripts";

    $rebuilding_usb_share = file_exists($printer_dir . '/rebuilding_usb') ? 'true' : 'false';
    $mono_x_enabled = file_exists($printer_dir . '/enable_mono_x') ? 'true' : 'false';
    $camera_enabled = file_exists($printer_dir . '/enable_camera') ? 'true' : 'false';
    $wifi_enabled = file_exists($printer_dir . '/enable_wifi_file') ? 'true' : 'false';
    $auto_shutdown = file_exists($printer_dir . '/enable_auto_shutdown') ? 'true' : 'false';

    if(file_exists($printer_dir . '/printer_ip') )
    {
        $output = shell_exec(escapeshellcmd('/var/www/html/includes/scripts/mono_x_status.py'));
        $printer_details = json_decode(str_replace("'",'"',$output));

        $printer_ip_address = $printer_details->ip_address;
        $printer_status = $printer_details->printer_status;

        if(strtolower($printer_status) == 'printing') {
            $print_job = explode('/',$printer_details->print_job);
            $print_job_name = $print_job[0];
        } else {
            $print_job = "";
            $job_details = "";
            $print_job_name = "";
        }

        $printer_files = str_replace(",end","",str_replace("getfile,","",$printer_details->files));

        $fileList = explode(",",$printer_files);
        natcasesort($fileList);

        $layers_complete = $printer_details->layers_complete;
        $layers_complete = $layers_complete ? $layers_complete : "";

        $percent_complete = $printer_details->percent_complete;
        $percent_complete = $percent_complete ? $percent_complete : "";

        $seconds_remaining = (int) $printer_details->seconds_remaining;
        $seconds_remaining = $seconds_remaining ? $seconds_remaining : "";

        $resin_required = $printer_details->resin_required;
        $resin_required = $resin_required ? $resin_required : "";

        $printer_connection = $printer_details->connection;
        $printer_connection = $printer_connection ? $printer_connection : "";
    }
    else 
    {
        $printer_files = "";
        $printer_ip_address =  "";
        $printer_status =  "";
        $print_job_name =  "";
        $layers_complete =  "";
        $percent_complete =  "";
        $seconds_remaining =  "";
        $resin_required =  "";
        $printer_connection =  "Not Connected";
    }
?>