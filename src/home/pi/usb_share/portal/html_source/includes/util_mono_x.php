<?php

$data = array();

if (empty($_GET['a'])) {
    $data['error']  = "no attributes provided." ;
}
else {
    $printer_dir = '/home/pi/.usb_share_resources/portal';
    $exec_dir = "/var/www/html/includes/scripts";
    $request_type = strtolower($_GET['a']);
    $data['error']  = "" ;

    #
    # get mono x status
    # 

    if($request_type == 'printer_status') {

        $data['mono_x_enabled'] = file_exists($printer_dir . '/enable_mono_x') ? 'true' : 'false';
        $data['wifi_enabled'] = file_exists($printer_dir . '/enable_wifi_file') ? 'true' : 'false';
        
        if(file_exists($printer_dir . '/printer_ip') )
        {
            $command = escapeshellcmd('./scripts/mono_x_status.py');
            $output = shell_exec($command);
            $printer_details = json_decode(str_replace("'",'"',$output));

            $printer_files = $printer_details->files;
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
            
            $layers_complete = $printer_details->layers_complete;
            $percent_complete = $printer_details->percent_complete;
            $seconds_remaining = (int) $printer_details->seconds_remaining;
            $resin_required = $printer_details->resin_required;
            $printer_connection = $printer_details->connection;
        
            $data['printer_files'] = str_replace(",end","",str_replace("getfile,","",$printer_files));
            $data['printer_ip_address'] = $printer_ip_address ? $printer_ip_address : "";
            $data['printer_status'] = $printer_status ? $printer_status : "";
            $data['print_job_name'] = $print_job_name;
            $data['layers_complete'] = $layers_complete ? $layers_complete : "";
            $data['percent_complete'] = $percent_complete ? $percent_complete : "";
            $data['seconds_remaining'] = $seconds_remaining ? $seconds_remaining : "";
            $data['resin_required'] = $resin_required ? $resin_required : "";
            $data['printer_connection'] = $printer_connection ? $printer_connection : "";
        }
        else 
        {
            $data['printer_files'] = "";
            $data['printer_ip_address'] =  "";
            $data['printer_status'] =  "";
            $data['print_job_name'] =  "";
            $data['layers_complete'] =  "";
            $data['percent_complete'] =  "";
            $data['seconds_remaining'] =  "";
            $data['resin_required'] =  "";
            $data['printer_connection'] =  "Not Connected";
        }
    } else if($request_type == 'enable_mono_x') {
        shell_exec('./scripts/rpi_update_mono_x.py -a enable;');
    } else if($request_type == 'disable_mono_x') {
        shell_exec('./scripts/rpi_update_mono_x.py -a disable;');
    } else if($request_type == 'update') {
        $printer_ip = strtolower($_GET["printer_ip"]);
        $enable_protection = $_GET["enable_print_protection"];
        $enable_wifi_file = $_GET["enable_wifi_file"];
    
        $cmd = 'sudo ./scripts/rpi_update_mono_x.py -a update --printer_ip ' . $printer_ip . ' --enable_wifi ' . $enable_wifi_file . ' --enable_protection ' . $enable_protection;
        shell_exec($cmd);
        $data['cmd'] = $cmd;
    } else if($request_type == 'mono_x_action') {
        $cmd = 'sudo ./scripts/mono_x_action.py ' . $_GET['cmd'];
        $command = escapeshellcmd($cmd);
        $output = shell_exec($command);
        $data = array();
        $data['status'] = $output;
    }
}

#
# return the results as a json string
#

echo json_encode($data);
