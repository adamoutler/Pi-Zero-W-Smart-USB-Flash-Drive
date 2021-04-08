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

#
# cleanup
#

rm -rf /home/pi/usb_share_upgrade
rm -f /home/pi/upgrade_usb_share.sh
