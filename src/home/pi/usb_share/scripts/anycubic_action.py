#!/usr/bin/env python3

import socket
import re
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("action")
args = parser.parse_args()
action = str(args.action)

HOST = ''
PORT = 6000

try:
    with open('/home/pi/usb_share/flags/printer_ip','r') as f:
        HOST = f.read().strip()
        f.close()
except:
    HOST = ''

try:
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.settimeout(5)
    s.connect((HOST,PORT))

    s.send(('go'+action+',').encode())
    status = s.recv(1024).decode('UTF-8')
    arr = status.split(',')
    response = arr[1]

    s.close()
except:
    response = 'Not Connected'

print(response)
