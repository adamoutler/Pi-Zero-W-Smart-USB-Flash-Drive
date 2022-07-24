#!/bin/bash

/usr/local/bin/mjpg_streamer -o "output_http.so -w ./www" -i "input_raspicam.so -x 640 -y 480 -fps 8 -rot 90"&
