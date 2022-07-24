<?php

$data = array();

if (empty($_GET['a'])) {
    $data['error']  = "no attributes provided." ;
}
else {
    $flags = '/home/pi/usb_share/flags';
    $exec_dir = "/home/pi/usb_share/scripts";
    $request_type = strtolower($_GET['a']);
    $data['error']  = "" ;

    #
    # get mono x status
    # 

    if($request_type == 'printer_status') {

        $data['anycubic_enabled'] = file_exists($flags . '/enable_anycubic') ? 'true' : 'false';
        $data['wifi_enabled'] = file_exists($flags . '/enable_wifi_file') ? 'true' : 'false';

        $command = escapeshellcmd('/home/pi/usb_share/scripts/anycubic_status.py');
        $output = shell_exec($command);
        $printer_details = json_decode(str_replace("'",'"',$output));
        $printer_connection = $printer_details->connection ? $printer_details->connection : "";
        $printer_ip_address = $printer_details->ip_address ? $printer_details->ip_address : "";
        $printer_model = $printer_details->printer_model ? $printer_details->printer_model : "";
        
        if(file_exists($flags . '/printer_ip') &&  strtolower($printer_connection) == "connected")
        {
            $printer_files = $printer_details->files ? $printer_details->files : "";
            $fileList = explode(",",$printer_files);
            natcasesort($fileList);
            $printer_files = implode(",",$fileList);
            
            $printer_status = $printer_details->printer_status ? $printer_details->printer_status : "";

            if(strtolower($printer_status) == 'printing') {
                $print_job = explode('/',$printer_details->print_job);
                $print_job_name = $print_job[0];
            } else {
                $print_job = "";
                $job_details = "";
                $print_job_name = "";
            }
            
            $layers_complete = $printer_details->layers_complete ? $printer_details->layers_complete : "";
            $percent_complete = $printer_details->percent_complete ? $printer_details->percent_complete : "";
            $seconds_remaining = (int) $printer_details->seconds_remaining ? $printer_details->seconds_remaining : 0;
            $resin_required = $printer_details->resin_required ? $printer_details->resin_required : "";
            
            $data['printer_ip_address'] = $printer_ip_address ? $printer_ip_address : "";
            $data['printer_model'] = $printer_model ? $printer_model : "";
            $data['printer_files'] = str_replace(",end","",str_replace("getfile,","",$printer_files));
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
            $data['printer_ip_address'] = $printer_ip_address ? $printer_ip_address : "";
            $data['printer_model'] = $printer_model ? $printer_model : "";
            $data['printer_files'] = "";
            $data['printer_status'] =  "";
            $data['print_job_name'] =  "";
            $data['layers_complete'] =  "";
            $data['percent_complete'] =  "";
            $data['seconds_remaining'] =  "";
            $data['resin_required'] =  "";
            $data['printer_connection'] =  "Not Connected";
        }
    } else if($request_type == 'enable_anycubic') {
        shell_exec('/home/pi/usb_share/scripts/anycubic_update.py -a enable;');
    } else if($request_type == 'disable_anycubic') {
        shell_exec('/home/pi/usb_share/scripts/anycubic_update.py -a disable;');
    } else if($request_type == 'update') {
        $printer_ip = strtolower($_GET["printer_ip"]);
        $printer_model = strtolower($_GET["printer_model"]);
        $enable_protection = $_GET["enable_print_protection"];
        $enable_wifi_file = $_GET["enable_wifi_file"];
    
        $cmd = 'sudo /home/pi/usb_share/scripts/anycubic_update.py -a update --printer_ip ' . $printer_ip . ' --printer_model ' . $printer_model . ' --enable_wifi ' . $enable_wifi_file . ' --enable_protection ' . $enable_protection;
        shell_exec($cmd);
        $data['cmd'] = $cmd;
    } else if($request_type == 'anycubic_action') {
        $cmd = 'sudo /home/pi/usb_share/scripts/anycubic_action.py ' . $_GET['cmd'];
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
