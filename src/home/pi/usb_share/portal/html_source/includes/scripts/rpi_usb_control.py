#!/usr/bin/env python3

import os
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("--action")

args = parser.parse_args()

action = str(args.action)



if action == 'reset':
    os.system("sudo rm -f /home/pi/.usb_share_resources/portal/usb_reset_status")

    os.system("sudo echo 'stopping_usb' > /home/pi/.usb_share_resources/portal/usb_reset_status")
    os.system("sudo modprobe -r g_mass_storage;")

    os.system("sudo echo 'starting_usb' > /home/pi/.usb_share_resources/portal/usb_reset_status")
    os.system("sudo modprobe g_mass_storage file=/home/pi/USB_Share/usbdisk.img stall=0 ro=0 removable=1;")

    os.system("sudo rm -f /home/pi/.usb_share_resources/portal/usb_reset_status")
elif action == 'unplug':
    os.system("sudo rm -f /home/pi/.usb_share_resources/portal/usb_reset_status")
    
    os.system("sudo echo 'stopping_usb' > /home/pi/.usb_share_resources/portal/usb_reset_status")
    os.system("sudo modprobe -r g_mass_storage;")

    os.system("sudo rm -f /home/pi/.usb_share_resources/portal/usb_reset_status")
