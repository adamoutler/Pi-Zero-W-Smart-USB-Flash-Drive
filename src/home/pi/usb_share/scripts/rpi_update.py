#!/usr/bin/env python3

import os
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("--current_hostname")
parser.add_argument("--new_hostname")
parser.add_argument("--enable_camera")
parser.add_argument("--reboot")
parser.add_argument("--shutdown")
parser.add_argument("--rotation")
args = parser.parse_args()

current_hostname = str(args.current_hostname)
new_hostname = str(args.new_hostname)
enable_camera = str(args.enable_camera)
shutdown = str(args.shutdown)
rotation = str(args.rotation)

if args.shutdown == 'yes':
    os.system("sudo rm -f /home/pi/usb_share/flags/updating_rpi")
    os.system("sudo halt")
elif args.reboot == 'yes':
    os.system("sudo rm -f /home/pi/usb_share/flags/updating_rpi")
    os.system("sudo reboot")
else :
    os.system("sudo rm -f /home/pi/usb_share/flags/updating_rpi")
    os.system("sudo echo 'updating_camera' > /home/pi/usb_share/flags/updating_rpi")

    if enable_camera == 'on':
        os.system("sudo touch /home/pi/usb_share/flags/enable_camera;")
        os.system("sudo cp /home/pi/usb_share/scripts/camera_templates/start_camera_stream_" + rotation + ".sh /usr/local/share/start_camera_stream.sh;")
        os.system("sudo rm /home/pi/usb_share/flags/rotate_*;")
        os.system("sudo touch /home/pi/usb_share/flags/rotate_" + rotation + ";")
    else :
        os.system("sudo rm /home/pi/usb_share/flags/enable_camera;")
        os.system("sudo cp /home/pi/usb_share/scripts/camera_templates/start_camera_stream_disabled.sh /usr/local/share/start_camera_stream.sh;")
        os.system("sudo rm /home/pi/usb_share/flags/rotate_*;")
        
    
    os.system("sudo echo 'updating_hostname' > /home/pi/usb_share/flags/updating_rpi")
    if current_hostname != "" and new_hostname != "" and current_hostname != new_hostname :
        os.system("sudo sed -i 's@" + current_hostname + "@" + new_hostname + "@g' /etc/hostname;")
        os.system("sudo sed -i 's@" + current_hostname.upper() + "@" + new_hostname + "@g' /etc/hostname;")
        os.system("sudo sed -i 's@" + current_hostname + "@" + new_hostname + "@g' /etc/hosts;" )
        os.system("sudo sed -i 's@" + current_hostname.upper() + "@" + new_hostname + "@g' /etc/hosts;")

    os.system("sudo echo 'reboot' > /home/pi/usb_share/flags/updating_rpi")




