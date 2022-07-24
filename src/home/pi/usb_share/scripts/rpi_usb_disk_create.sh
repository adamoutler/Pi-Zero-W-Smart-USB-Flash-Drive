#! /bin/bash -
if [ "$#" -gt 0 ]; then
    if echo $1 | egrep -q '^[0-9]+$'; then
        if [ ! -f /home/pi/usb_share/flags/rebuilding_usb ]; then
            gb_count="$1";
            block_count=$((gb_count * 1024));

            rm -f /home/pi/usb_share/flags/rebuilding_usb;

            # Stop services
            echo "stop_services" > /home/pi/usb_share/flags/rebuilding_usb;
            service smbd stop;
            modprobe -r g_mass_storage;
            umount /home/pi/usb_share/upload;

            sleep 2;

            # remove disk image
            echo "delete_image" > /home/pi/usb_share/flags/rebuilding_usb;
            rm -f /home/pi/usb_share/usb_share_disk.img;

            sleep 2;


            #create new disk image
            echo "create_image" > /home/pi/usb_share/flags/rebuilding_usb;
            #dd bs=1M if=/dev/zero of=/home/pi/usb_share/usb_share_disk.img count=$block_count;
            dd bs=1M if=/dev/zero of=/home/pi/usb_share/usb_share_disk.img count=0 seek=$block_count;

            sleep 2;

            #create file system
            echo "create_fs" > /home/pi/usb_share/flags/rebuilding_usb;
            mkdosfs /home/pi/usb_share/usb_share_disk.img -F 32 -I;

            sleep 2;

            # start services
            #echo "start_services" > /home/pi/usb_share/flags/rebuilding_usb;
            #mount /home/pi/usb_share/upload;
            #modprobe g_mass_storage file=/home/pi/usb_share/usb_share_disk.img stall=0 ro=0 removable=1;
            #service smbd start;

            #sleep 3;

            # reboot server
            echo "reboot" > /home/pi/usb_share/flags/rebuilding_usb;

            sleep 3;
        fi
    fi
    
    if echo $1 | egrep -q "reboot"; then 
            rm -f /home/pi/usb_share/flags/rebuilding_usb;
            reboot;
    fi
fi
