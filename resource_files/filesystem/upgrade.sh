# System changes

chmod a-w /home/pi/usb_share/scripts/camera_templates/*
sudo cp -f /tmp/2_4_4_resources/usr_local_share/* /usr/local/share
sudo chmod 755 /usr/local/share/*sh
sudo chmod 755 /usr/local/share/*py

sudo cp -f /tmp/2_4_4_resources/usr_local_bin/upgrade_usb_share /usr/local/bin/upgrade_usb_share
sudo chmod 755 /usr/local/bin/upgrade_usb_share

sudo crontab /tmp/2_4_4_resources/etc/crontab

sudo cp -f /tmp/2_4_4_resources/php/php.ini /etc/php/7.3/apache2/php.ini

#portal changes
sudo sed -i 's/640/320/g' /home/pi/usb_share/scripts/camera_templates/*.sh
sudo sed -i 's/480/240/g' /home/pi/usb_share/scripts/camera_templates/*.sh

sudo cp -f /home/pi/usb_share/scripts/camera_templates/start_camera_stream_disabled.sh /usr/local/share/start_camera_stream.sh
sudo rm -f /home/pi/usb_share/flags/enable_camera
sudo rm -f /home/pi/usb_share/flags/rotate_*

chmod a+w /home/pi/usb_share/html_root/*php
cp -f /tmp/2_4_4_resources/html_root/* /home/pi/usb_share/html_root
chmod a-w /home/pi/usb_share/html_root/*php

chmod a+w /home/pi/usb_share/html_root/includes/*php
cp -f /tmp/2_4_4_resources/includes/* /home/pi/usb_share/html_root/includes
chmod a-w /home/pi/usb_share/html_root/includes/*php

chmod a+w /home/pi/usb_share/html_root/img/*
cp -f /tmp/2_4_4_resources/img/* /home/pi/usb_share/html_root/img
chmod a-w /home/pi/usb_share/html_root/img/*

sudo chown pi /home/pi/usb_share/flags/current_version.txt
sudo chgrp pi /home/pi/usb_share/flags/current_version.txt
chmod a+w /home/pi/usb_share/flags/current_version.txt
cp -f /tmp/2_4_4_resources/flags/current_version.txt /home/pi/usb_share/flags/current_version.txt

exit 0