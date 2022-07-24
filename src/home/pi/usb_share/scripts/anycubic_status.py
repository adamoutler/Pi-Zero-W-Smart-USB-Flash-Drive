#!/usr/bin/env python3

import socket
import re

HOST = ''
PORT = 6000
MODEL = ''

try:
    with open('/home/pi/usb_share/flags/printer_ip','r') as f:
        HOST = f.read().strip()
        f.close()
except:
    HOST = ''

try:
    with open('/home/pi/usb_share/flags/printer_model','r') as f:
        MODEL = f.read().strip()
        f.close()
except:
    MODEL = ''

data = {}
data['ip_address'] = HOST
data['printer_model'] = MODEL

try:
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.settimeout(5)
    s.connect((HOST,PORT))

    s.send(b'getfiles,')
    data['files'] = s.recv(1024).decode('UTF-8')

    s.send(b'getmode,')
    data['mode'] = s.recv(1024).decode('UTF-8')

    s.send(b'getstatus,')
    STATUS = s.recv(1024).decode('UTF-8')
    arr = STATUS.split(',')

    data['status_raw'] = STATUS

    if arr[1] == 'print':
        data['printer_status'] = 'Printing'
    elif arr[1] == 'pause':
        data['printer_status'] = 'Paused'
    else:
        data['printer_status'] = 'Stopped'

    if arr[1] == 'print':
        data['print_job'] = arr[2]
        data['percent_complete'] = str(arr[4]) + "%"
        data['layers_complete'] = str(arr[5]) + " / " + str(arr[3])
        data['seconds_remaining'] = arr[7]
        data['resin_required'] = arr[8]
    else:
        data['print_job'] = ''
        data['total_layers'] = ''
        data['layers_complete'] = ''
        data['percent_complete'] = ''
        data['seconds_remaining'] = ''
        data['time_remaining'] = ''
        data['resin_required'] = ''

    data['connection'] = 'Connected'

    s.close()
except:
    data['connection'] = 'Not Connected'

print(data)
