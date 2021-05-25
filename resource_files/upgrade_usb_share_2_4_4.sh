cd /tmp
wget https://raw.githubusercontent.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/main/resource_files/2_4_4_resources.zip
unzip 2_4_4_resources.zip 
rm 2_4_4_resources.zip 

2_4_4_resources/upgrade.sh

rm -rf 2_4_4_resources
rm -f upgrade_usb_share.sh