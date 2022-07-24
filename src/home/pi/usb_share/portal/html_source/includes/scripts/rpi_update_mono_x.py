#!/usr/bin/env python3

import os
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("-a")
parser.add_argument("--printer_ip")
parser.add_argument("--enable_wifi")
parser.add_argument("--enable_protection")
args = parser.parse_args()

action = str(args.a)
printer_ip = str(args.printer_ip)
enable_wifi = str(args.enable_wifi)
enable_protection = str(args.enable_protection)

if action == 'update':

    os.system('sudo rm -f /home/pi/.usb_share_resources/portal/printer_ip;')
    os.system('sudo touch /home/pi/.usb_share_resources/portal/printer_ip;')
    os.system('sudo chmod 666 /home/pi/.usb_share_resources/portal/printer_ip;')
    os.system('sudo echo ' + printer_ip + ' >> /home/pi/.usb_share_resources/portal/printer_ip;')


    if enable_wifi == 'on' :
        os.system('sudo rm -f /home/pi/.usb_share_resources/portal/enable_wifi_file;')
        os.system('sudo touch /home/pi/.usb_share_resources/portal/enable_wifi_file;')
        os.system('sudo systemctl daemon-reload;')
        os.system('sudo systemctl enable mono_x_wifi.service;')
        os.system('sudo systemctl start mono_x_wifi.service;')
    else :
        os.system('sudo systemctl stop mono_x_wifi.service;' )
        os.system('sudo systemctl disable mono_x_wifi.service;')
        os.system('sudo systemctl daemon-reload;')
        os.system('sudo rm -f /home/pi/USB_Share/upload/WIFI.txt')
        os.system('sudo rm -f /home/pi/.usb_share_resources/portal/enable_wifi_file;')

elif action == 'enable':
    os.system('sudo touch /home/pi/.usb_share_resources/portal/enable_mono_x;')
elif action == 'disable':
    os.system('sudo rm -f /home/pi/.usb_share_resources/portal/printer_ip;')
    os.system('sudo rm -f /home/pi/.usb_share_resources/portal/enable_wifi_file;')
    os.system('sudo rm -f /home/pi/.usb_share_resources/portal/enable_mono_x;')