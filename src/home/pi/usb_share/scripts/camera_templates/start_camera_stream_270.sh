#!/bin/bash

/usr/local/bin/mjpg_streamer -o "output_http.so -w ./www" -i "input_raspicam.so -x 320 -y 240 -fps 5 -rot 270"&
