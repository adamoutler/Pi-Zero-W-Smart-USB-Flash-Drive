#
# get content from create-more
#

if [ -d "/home/pi/usb_share_upgrade" ]; then 
        rm -rf /home/pi/usb_share_upgrade
fi

wget https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/usb_share_upgrade.zip
unzip -o /home/pi/usb_share_upgrade.zip
rm -rf /home/pi/usb_share_upgrade.zip

#
# Setup portal
#

sudo rm -rf /var/www/html/*
sudo rm -rf /home/pi/.usb_share_resources/portal/*

sudo cp -R /home/pi/usb_share_upgrade/portal/html_source/* /var/www/html/

sudo cp -R /home/pi/usb_share_upgrade/portal/html_source /home/pi/.usb_share_resources/portal/
sudo cp -R /home/pi/usb_share_upgrade/portal/scripts /home/pi/.usb_share_resources/portal/
sudo cp -R /home/pi/usb_share_upgrade/portal/system_files /home/pi/.usb_share_resources/portal/

sudo cp -R /home/pi/usb_share_upgrade/setup/system_files/upgrade_usb_share /usr/local/bin/
sudo cp -f /home/pi/usb_share_upgrade/portal/current_version.txt /home/pi/.usb_share_resources/portal/current_version.txt

sudo chmod 777 /home/pi/.usb_share_resources/portal/scripts/*
sudo chmod 777 /usr/local/bin/upgrade_usb_share
sudo chmod -R a+w /var/www/html/*
sudo chmod -R a+r /var/www/html/*
sudo chmod -R a+w /home/pi/.usb_share_resources/portal/*
sudo chmod -R a+r /home/pi/.usb_share_resources/portal/*

#
# cleanup
#

rm -rf /home/pi/usb_share_upgrade
rm -f /home/pi/upgrade_usb_share.sh
