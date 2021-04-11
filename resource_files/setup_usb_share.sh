#
# Remove unwanted software
#

apt-get remove --purge libreoffice* -y
apt-get purge wolfram-engine -y


#
# Install new packages
#

apt-get update
apt-get install samba winbind python3-pip apache2 php libapache2-mod-php gcc g++ cmake libjpeg8-dev imagemagick libv4l-dev arp-scan -y
pip3 install watchdog

apt-get clean
apt-get autoremove -y


#
# get content from create-more
#

if [ -d "/home/pi/usb_share_full_install" ]; then 
        rm -rf /home/pi/usb_share_full_install
fi

wget https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/usb_share_full_install.zip
unzip -o /home/pi/usb_share_full_install.zip
rm -rf /home/pi/usb_share_full_install.zip


#
# modify system files
#

if ! grep  "dtoverlay=dwc2" /boot/config.txt; then
        echo "dtoverlay=dwc2" >> /boot/config.txt;
fi

if ! grep "dwc2" /etc/modules; then 
        echo "dwc2" >> /etc/modules;
fi

#cp /etc/fstab /home/pi/usb_share_full_install/setup/system_files/fstab
#chmod 666 /home/pi/usb_share_full_install/setup/system_files/fstab
#echo "/home/pi/USB_Share/usbdisk.img /home/pi/USB_Share/upload vfat users,umask=000 0 2" >> /home/pi/usb_share_full_install/setup/system_files/fstab
#cp 	/home/pi/usb_share_full_install/setup/system_files/fstab /etc/fstab

cp -R /home/pi/usb_share_full_install/setup/system_files/prep_* /usr/local/bin/
cp /home/pi/usb_share_full_install/setup/system_files/monox_wifi.py /usr/local/share/monox_wifi.py
cp /home/pi/usb_share_full_install/setup/system_files/usbshare.py /usr/local/share/usbshare.py
chmod a+x /usr/local/share/*.py
chmod a+x /usr/local/bin/prep_*

cp /home/pi/usb_share_full_install/setup/system_files/sudoers /etc/sudoers 


#
# create network share
#

if [ -d "/home/pi/USB_Share" ]; then 
        rm -rf /home/pi/USB_Share
fi

if [ -d "/home/pi/.usb_share_resources" ]; then 
        rm -rf /home/pi/.usb_share_resources
fi

mkdir /home/pi/USB_Share
mkdir /home/pi/.usb_share_resources
mkdir /home/pi/USB_Share/upload

if [ "$#" -ne 1 ]; then
        dd bs=1M if=/dev/zero of=/home/pi/USB_Share/usbdisk.img count=2048
else 
        dd bs=1M if=/dev/zero of=/home/pi/USB_Share/usbdisk.img count=$1
fi

mkdosfs /home/pi/USB_Share/usbdisk.img -F 32 -I

echo "/home/pi/USB_Share/usbdisk.img /home/pi/USB_Share/upload vfat users,umask=000 0 2" >> /etc/fstab
mount -a

cp /home/pi/usb_share_full_install/portal/system_files/smb.conf /etc/samba/smb.conf
service smbd restart

cp /home/pi/usb_share_full_install/setup/system_files/usbshare.service /etc/systemd/system
systemctl daemon-reload
systemctl enable usbshare.service
systemctl start usbshare.service

#
# Setup portal
#

rm -rf /var/www/html/*
rm -rf /home/pi/.usb_share_resources/portal/html_source/*
rm -rf /home/pi/.usb_share_resources/portal/scripts/*

cp -R /home/pi/usb_share_upgrade/portal/html_source/* /var/www/html/
cp -R /home/pi/usb_share_upgrade/portal/html_source/* /home/pi/.usb_share_resources/portal/html_source/
cp -R /home/pi/usb_share_upgrade/portal/scripts/* /home/pi/.usb_share_resources/portal/scripts/
cp -R /home/pi/usb_share_upgrade/setup/system_files/upgrade_usb_share /usr/local/bin/
cp -f /home/pi/usb_share_upgrade/portal/current_version.txt /home/pi/.usb_share_resources/portal/current_version.txt

chmod 777 /home/pi/.usb_share_resources/portal/scripts/*
chmod 777 /usr/local/bin/upgrade_usb_share
chmod -R a+w /var/www/html/*
chmod -R a+w /home/pi/.usb_share_resources/portal/*
chmod -R a+r /var/www/html/*
chmod -R a+r /home/pi/.usb_share_resources/portal/*

sed -i 's@upload_max_filesize = 2M@upload_max_filesize = 128M@g' /etc/php/7.3/apache2/php.ini
sed -i 's@post_max_size = 8M@post_max_size = 128M@g' /etc/php/7.3/apache2/php.ini

cp /home/pi/usb_share_full_install/setup/system_files/mono_x_wifi.service /etc/systemd/system
systemctl daemon-reload
systemctl enable mono_x_wifi.service
systemctl start mono_x_wifi.service

#
# Setup camera
#

ln -s /usr/include/linux/videodev2.h /usr/include/linux/videodev.h

mkdir /home/pi/.usb_share_resources/camera
cd /home/pi/.usb_share_resources/camera
wget https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/mjpg-streamer-master.zip
unzip -o /home/pi/.usb_share_resources/camera/mjpg-streamer-master.zip
rm -f /home/pi/.usb_share_resources/camera/mjpg-streamer-master.zip
mv /home/pi/.usb_share_resources/camera/mjpg-streamer-master/mjpg-streamer-experimental /home/pi/.usb_share_resources/camera/
rm -rf /home/pi/.usb_share_resources/camera/mjpg-streamer-master/

cd /home/pi/.usb_share_resources/camera/mjpg-streamer-experimental
make
make install

cp /home/pi/.usb_share_resources/portal/system_files/start_camera_stream_disabled.sh /usr/local/share/start_camera_stream.sh
chmod a+x /usr/local/share/start_camera_stream.sh

crontab /home/pi/usb_share_full_install/setup/system_files/crontab

if grep "start_x=1" /boot/config.txt; then
	exit
elif grep "start_x=0" /boot/config.txt; then
        sed -i "s/start_x=0/start_x=1/g" /boot/config.txt
else 
	echo "start_x=1"  >> /boot/config.txt
fi

if grep "gpu_mem=" /boot/config.txt; then
	exit
else 
	echo "gpu_mem=128"  >> /boot/config.txt
fi

# 
# change host name
#

cp /home/pi/usb_share_full_install/setup/system_files/hosts /etc/hosts
cp /home/pi/usb_share_full_install/setup/system_files/hostname /etc/hostname


#
# cleanup
#

rm -rf /home/pi/usb_share_full_install
rm -f /home/pi/setup_usb_share.sh

#
# restart system
#

reboot