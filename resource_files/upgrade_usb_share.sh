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
# Setup portal
#

sudo rm -rf /var/www/html/*
sudo rm -rf /home/pi/.usb_share_resources/portal/html_source
sudo rm -rf /home/pi/.usb_share_resources/portal/scripts
sudo rm -rf /home/pi/.usb_share_resources/portal/system_files

sudo cp -R /home/pi/usb_share_full_install/portal/html_source/* /var/www/html/

sudo cp -R /home/pi/usb_share_full_install/portal/html_source /home/pi/.usb_share_resources/portal
sudo cp -R /home/pi/usb_share_full_install/portal/system_files /home/pi/.usb_share_resources/portal

sudo cp -R /home/pi/usb_share_full_install/setup/system_files/upgrade_usb_share /usr/local/bin/
sudo cp -R /home/pi/usb_share_full_install/setup/system_files/beta_upgrade_usb_share /usr/local/bin/
sudo cp -f /home/pi/usb_share_full_install/portal/current_version.txt /home/pi/.usb_share_resources/portal/current_version.txt

sudo chmod 777 /usr/local/bin/upgrade_usb_share
sudo chmod 777 /usr/local/bin/beta_upgrade_usb_share
sudo chmod -R a+w /var/www/html/*
sudo chmod -R a+r /var/www/html/*
sudo chmod -R a+w /home/pi/.usb_share_resources/portal/*
sudo chmod -R a+r /home/pi/.usb_share_resources/portal/*

sudo cp /home/pi/usb_share_full_install/setup/system_files/mono_x_wifi.service /etc/systemd/system

#
# cleanup
#

rm -rf /home/pi/usb_share_full_install
rm -f /home/pi/upgrade_usb_share.sh
