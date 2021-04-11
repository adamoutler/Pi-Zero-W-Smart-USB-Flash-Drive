# Pi Zero W Smart USB Flash Drive
SMB enabled network share that appears as a USB drive to a connected device

### Acknowledgements

I based this project on the work documented in the MagPi magazine [article](https://magpi.raspberrypi.org/articles/pi-zero-w-smart-usb-flash-drive) by the same name.  It was written by [Russell Barnes](https://magpi.raspberrypi.org/articles/author/77pb3df8MQLs3i8qTd0C8Q). It also borrows some ideas from [Giles Davison's](https://github.com/gilesdavison) now defunct RadaDASH project.

### Features
#### Version 1.0
* Remotely accessible USB device (uploading, deleting)
* 2GB shared storage pre-configured (Default)
* Can be used as a USB drive on any device (including Mac, Windows, TV, and 3D printers)
* After updates on the network-share, the Pi waits 30 seconds and then automatically disconnects (unplugs) 
  and reconnects the USB interface so changes appear on the target device
* Create ANYCUBIC WIFI.txt file based on the Raspberry Pi's configuration

#### Version 2.1.5
* Web-based management portal
  * Local storge management (Upload/delete)
  * Network scanning
  * RPi Camera Streaming
  * Mono X printing control
  * Settings
    * Update hostname
    * Enable camera streaming
    * Enable Mono X features
  * RPi power management (reboot / shutdown)
  * RPi eject / reset virtual USP drive

![Summary Example](https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/blob/main/screenshots/USB-Share-Summary.png)

![Mono X Example](https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/blob/main/screenshots/USB-Share-Mono-X-Control.png)

![Settings Example](https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/blob/main/screenshots/USB-Share-Settings.png)

#### Version 2.2.0
* Added the ability to resize teh USB partition from the Settings page
* Added ability to upgrade without re-imaging SDCard (CLI from v2.1.x; GUI going forward)

![Settings Example](https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/raw/main/screenshots/USB-Share-Settings-2-2-0.png)

#### Version 2.2.1
* Add progress indicators to the Mono X print controls.
* Updated the Mono X management screen to use async calls to get printer stand and eliminate browser reloads.
* Separated the printer file listing from the printer progress UIs.
      - Printer file list shown while printer is stopped; hidden while printer is running.

![Mono X File Listing](https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/raw/main/screenshots/USB-Share--Mono-X-File-Listing.png)
![Mono X Printer Progress](https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/raw/main/screenshots/USB-Share--Mono-X-Print-Progress.png)


### 3D Printer Support

This project should work for most (if not all) 3D printers as a USB drive.

However, there is an additional feature for the [ANYCUBIC Photon Mono X](https://www.anycubic.com/products/photon-mono-x-resin-printer) printer. There is an optional service that can be enabled to dynamically create a WIFI.txt file in the ANYCUBIC format to help configure the printer to match the network configuration on the Raspberry Pi W.

I don't have other printers to test, so I don't know how they are configured.  If they are similar, I'm happy to work with you add support in a future release. Open an issue and make a suggestion.

### Prerequisites

* MicroSD Card >= 8GB
* MicroSD Card Adapter/Reader
* USB to Micro USB Cable
* [Raspberry Pi Zero W](https://www.raspberrypi.org/products/raspberry-pi-zero-w/)*

*Note, this solution should work on any Raspberry Pi's that supports USB OTG (On-The-Go). This includes the Raspberry Pi 4 (via the USB-C port), but testing, and offical support, is limited to the Zero W.  

### Usage

1. Create SD Card from the provided image file
2. Connect Raspberry Pi Zero W using the **USB port** on the Pi
3. Manage files on the Raspberry Pi from a network share (SMB://)
4. Access the files from the USB interface (after 30 sec)

__* Note: Do not add/remove files while you are printing/watching a file from the USB drive.  When you make changes to the stored files, the USB will appear to unplug and reconnect.  This will disrupt any current files being used. *__


# Setup Instructions

### Create SD Card

Step 1. Download the latest [release](https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/releases/tag/2.2.1-release).

Step 2. Extract the image from the zip archive.

Step 3. Copy Image to SD Card

(MacOS) Connect the MicroSD card to your computer and flash the usb_share image using [balenaEtcher](https://www.balena.io/etcher/).
(Windows) Connect the MicroSD card to your computer and flash the usb_share image using [Win32DiskImager](https://sourceforge.net/projects/win32diskimager/).

Step 4. Edit the [wpa_supplicant.conf](https://github.com/tds2021/Pi-Zero-W-Smart-USB-Flash-Drive/blob/main/wpa_supplicant.conf) file provided, changing the placeholders network and password to your wireless networks SSID and password.

Step 5. Copy the *wpa_supplicant.conf* file to the boot partition of the SD card.


### Powering the Raspberry Pi

On the Pi Zero W, you'll see two micro USB ports. One is marked 'USB' and the other 'PWR IN.' You can supply power through either port, but the USB port is for data as well. There are two options.

You can connect your 3D printer into the Pi Zero W USB port, not the PWR IN port, using a standard micro USB cable. The cable will both supply power from the printer and make the USB data connection. The disadvantage is that the printer must be switched on to supply power to the Pi. When someone turns the printer off, the Pi will also lose power, which will also make it no longer available on the network.

Alternatively, you can connect a separate, always-on power supply to the PWR IN port and use a slightly modified micro USB cable to connect the printer to the USB port. The modification is to cut the red wire inside the micro USB cable. This protects the Pi from damage that could be caused by drawing power from two different power sources. The advantage of this method is that the Pi is powered independently from the printer. It will be available on the network even if the printer is off, and there is a reduced risk of sudden power loss and SD card corruption.

You might want to test the system with the first option and then move onto the second when you want a more permanent setup. ** Don't forget to cut the red wire if you use the second option.**

Connect the Pi Zero W USB port to the printer using your chosen method, power everything up.



### Connection to the Management Portal (New)

Default URL: http://3dprinter.local or http://[IP ADDRESS]

Port: 80

After the Raspberry Pi has fully booted, you can now access the management protal from any local network web browser. This all featurs of the USB Sahre devices can be managed from this portal other than re-sizing the size fo teh USB drive partition; that must still be done from  command line.



### Connecting to the Network Share

Default Host Name: 3dprinter

Now we can try to access the share from a Windows PC or a Mac. You'll need the hostname the Raspberry Pi is using or its IP Address. By default, the hostname will be **3DPRINTER**.

In Windows, you can bring up Explorer (Windows key + E) and type \\3dprinter (or \\[IP ADDRESS]) into the address bar at the top. The Run dialogue also works (Windows key + R).

On macOS, the Raspberry Pi will show up in the Finder sidebar. Alternatively, from the Finder menu, select Go Connect to server (Apple key + K) and type smb://3dprinter (or smb://[IP ADDRESS]) as the server address.

Once connected, you will see a shared named _USB_ where you can load your files. For your update to appear on the printer, it must first disconnect from the printer and then reconnect. Whenever you copy files over to the network-share, or delete them, the USB device should automatically reconnect to the printer after 30 seconds of inactivity.

**After you add files to the drive, you need to wait 30 seconds. After that, the Pi will disconenct and re-connect the USB interface so your changes can appear**

# Optional configurations

The following optional configurations can be made from the Pi console or over SSH.

Default userID: pi

Default password: raspberry

### Change the size of the USB drive (based on available space)

By default, the USB drive is configured as a 2GB drive.  This is set to accommodate smaller SD Cards. However, you can change this by recreating the image file on the Raspberry Pi.

Step 1. Login to the Raspberry Pi using the UserID/Password combination provided above.

Step 2. Stop the USB OTG service
```
sudo modprobe -r g_mass_storage
```

Step 3. Unmount the existing disk image
```
sudo umount /home/pi/USB_Share/upload
```

Step 4. Remove the existing disk image file
```
sudo rm /home/pi/USB_Share/usbdisk.img
```
Step 5. Check the available free space

The command df -h shows your disk space usage. Look at the Avail column for /dev/root to see how much free space you have.
```
df -h
```

Step 6. Create a new disk image file

The command below will create an empty 2GB binary file, change the count=2048 parameter to your new size (i.e., 10 GB = 10240, 25 GB = 25600, 48 GB = 49152). **keep a couple of GB free**; if you fill the card completely full, the image will become corrupt while running.

Creating the disk image will take a long time to complete. Seriously, walk away... the Pi W is slow, but it will finish.
```
sudo dd bs=1M if=/dev/zero of=/home/pi/USB_Share/usbdisk.img count=2048
```

Step 7. Formate the disk image as FAT32
```
sudo mkdosfs /home/pi/USB_Share/usbdisk.img -F 32 -I
```

Step 8. Remount the disk image
```
sudo mount -a
```

Step 9. Start the USB OTG service
```
sudo modprobe g_mass_storage file=/home/pi/USB_Share/usbdisk.img stall=0 ro=0 removable=1
```

Step 10. Restart the Raspberry Pi
```
sudo reboot
```

### Changing the Host Name (CLI)

By default, the USB device will be named #DPRINTER.  However, you can change this if you wish by updating the hostname on the Pi.

Step 1. Login to the Raspberry Pi using the UserID/Password combination provided above.

Step 2. Change the name in the /etc/hostname file
```
sudo nano /etc/hostname
```
Press CTRL+O followed by Enter to save, and then CTRL+X to quit.

Step 3. Change the name in the /etc/hosts file
```
sudo nano /etc/hosts
```
Press CTRL+O followed by Enter to save, and then CTRL+X to quit.

Step 3. Restart the Raspberry Pi
```
sudo reboot
```

### Enabling the ANYCUBIC WIFI service (CLI)

The ANYCUBIC Mono X printer has built-in WIFI capabilities.  This printer option is configured using a text file named _WIFI.txt_ that is _printed_ on the printer.  If you wish to have this file automatically created and added to the available files on the USB drive, based on what was configured in the wpa_supplicant.conf file, follow these instructions.

Step 1. Login to the Raspberry Pi using the UserID/Password combination provided above.

Step 2. Run the following commands:
```
sudo systemctl daemon-reload
sudo systemctl enable monox_wifi.service
sudo systemctl start monox_wifi.service
```
