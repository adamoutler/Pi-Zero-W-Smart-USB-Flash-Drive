#! /bin/bash -
if [ "$#" -gt 0 ]; then
    if echo $1 | egrep -q '^[0-9]+$'; then
        if [ ! -f /home/pi/.usb_share_resources/portal/rebuilding_usb ]; then
            gb_count="$1";
            block_count=$((gb_count * 1024));

            rm -f /home/pi/.usb_share_resources/portal/rebuilding_usb;

            # Stop services
            echo "stop_services" > /home/pi/.usb_share_resources/portal/rebuilding_usb;
            service smbd stop;
            modprobe -r g_mass_storage;
            umount /home/pi/USB_Share/upload;

            sleep 3;

            # remove disk image
            echo "delete_image" > /home/pi/.usb_share_resources/portal/rebuilding_usb;
            rm -f /home/pi/USB_Share/usbdisk.img;

            sleep 3;


            #create new disk image
            echo "create_image" > /home/pi/.usb_share_resources/portal/rebuilding_usb;
            dd bs=1M if=/dev/zero of=/home/pi/USB_Share/usbdisk.img count=$block_count;

            sleep 3;

            #create file system
            echo "create_fs" > /home/pi/.usb_share_resources/portal/rebuilding_usb;
            mkdosfs /home/pi/USB_Share/usbdisk.img -F 32 -I;

            sleep 3;

            # start services
            echo "start_services" > /home/pi/.usb_share_resources/portal/rebuilding_usb;
            mount /home/pi/USB_Share/upload;
            modprobe g_mass_storage file=/home/pi/USB_Share/usbdisk.img stall=0 ro=0 removable=1;
            service smbd start;

            sleep 3;

            # reboot server
            echo "reboot" > /home/pi/.usb_share_resources/portal/rebuilding_usb;

            sleep 3;
        fi
    fi
    
    if echo $1 | egrep -q "reboot"; then 
            rm -f /home/pi/.usb_share_resources/portal/rebuilding_usb;
            reboot;
    fi
fi
