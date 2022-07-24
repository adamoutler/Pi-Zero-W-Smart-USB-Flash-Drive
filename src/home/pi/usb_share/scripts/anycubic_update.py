#!/usr/bin/env python3

import os
import argparse

flag_dir = "/home/pi/usb_share/flags"
script_dir = "/home/pi/usb_share/scripts"
usb_share_dir = "/mnt/usb_share"

parser = argparse.ArgumentParser()
parser.add_argument("-a")
parser.add_argument("--printer_ip")
parser.add_argument("--printer_model")
parser.add_argument("--enable_wifi")
parser.add_argument("--enable_protection")
args = parser.parse_args()

action = str(args.a)
printer_ip = str(args.printer_ip)
printer_model = str(args.printer_model)
enable_wifi = str(args.enable_wifi)
enable_protection = str(args.enable_protection)

if action == "update":

    os.system("sudo rm -f " + flag_dir + "/printer_ip;")
    os.system("sudo touch " + flag_dir + "/printer_ip;")
    os.system("sudo chmod 666 " + flag_dir + "/printer_ip;")
    os.system("sudo echo " + printer_ip + " >> " + flag_dir + "/printer_ip;")
    
    os.system("sudo rm -f " + flag_dir + "/printer_model;")
    os.system("sudo touch " + flag_dir + "/printer_model;")
    os.system("sudo chmod 666 " + flag_dir + "/printer_model;")
    os.system("sudo echo " + printer_model + " >> " + flag_dir + "/printer_model;")


    if enable_wifi == "on" :
        os.system("sudo rm -f " + flag_dir + "/enable_wifi_file;")
        os.system("sudo touch " + flag_dir + "/enable_wifi_file;")
        os.system("sudo systemctl daemon-reload;")
        os.system("sudo systemctl enable anycubic_wifi.service;")
        os.system("sudo systemctl start anycubic_wifi.service;")
    else :
        os.system("sudo systemctl stop anycubic_wifi.service;" )
        os.system("sudo systemctl disable anycubic_wifi.service;")
        os.system("sudo systemctl daemon-reload;")
        os.system("sudo rm -f " + usb_share_dir + "/WIFI.txt")
        os.system("sudo rm -f " + flag_dir + "/enable_wifi_file;")

elif action == "enable":
    os.system("sudo touch " + flag_dir + "/enable_anycubic;")
    print("sudo touch " + flag_dir + "/enable_anycubic;")
elif action == "disable":
    os.system("sudo rm -f " + flag_dir + "/printer_ip;")
    os.system("sudo rm -f " + flag_dir + "/printer_model;")
    os.system("sudo rm -f " + flag_dir + "/enable_wifi_file;")
    os.system("sudo rm -f " + flag_dir + "/enable_anycubic;")
