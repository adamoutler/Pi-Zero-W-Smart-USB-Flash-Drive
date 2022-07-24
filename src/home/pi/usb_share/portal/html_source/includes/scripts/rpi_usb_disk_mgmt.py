#!/usr/bin/env python3

import os
import argparse
import math

parser = argparse.ArgumentParser()
parser.add_argument("-a", action='store_true')
parser.add_argument("-u", action='store_true')
parser.add_argument("-umb", action='store_true')
parser.add_argument("-m", action='store_true')


args = parser.parse_args()

# get available space
path = '/'
st = os.statvfs(path)
bytes_avail = (st.f_bavail * st.f_frsize)
gigabytes = bytes_avail / 1024 / 1024 / 1024
available_space = round(gigabytes,2)

# get the current disk size
used_bytes = os.path.getsize('/home/pi/USB_Share/usbdisk.img')
megabytes = math.floor(used_bytes / 1024 / 1024)
gigabytes = math.floor(used_bytes / 1024 / 1024 / 1024)
used_space = gigabytes

if args.a :
    print(available_space)
elif args.u :
    print ('%.2f' % gigabytes)
elif args.umb :
    print (megabytes)
elif args.m :
    max = math.floor(available_space + used_space - 2)
    print (max)

