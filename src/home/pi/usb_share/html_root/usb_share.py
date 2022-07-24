#!/usr/bin/python3
import socket
import time
import os
from watchdog.observers import Observer
from watchdog.events import *

#CMD_MOUNT = "modprobe g_mass_storage file=/home/pi/usb_share/usb_share_disk.img stall=0 ro=0 removable=1"
#CMD_UNMOUNT = "modprobe -r g_mass_storage"

CMD_UNMOUNT = "sudo /home/pi/usb_share/scripts/rpi_usb_control.py --action 'unplug';"
CMD_MOUNT = "sudo /home/pi/usb_share/scripts/rpi_usb_control.py --action 'reset';"
CMD_SYNC = "sync"

WATCH_PATH = "/home/pi/usb_share/upload"
ACT_EVENTS = [DirDeletedEvent, DirMovedEvent, FileDeletedEvent, FileModifiedEvent, FileMovedEvent]
ACT_TIME_OUT = 25

class DirtyHandler(FileSystemEventHandler):
    def __init__(self):
        self.reset()

    def on_any_event(self, event):
        if type(event) in ACT_EVENTS:
            self._dirty = True
            self._dirty_time = time.time()

    @property
    def dirty(self):
        return self._dirty

    @property
    def dirty_time(self):
        return self._dirty_time

    def reset(self):
        self._dirty = False
        self._dirty_time = 0
        self._path = None


os.system(CMD_MOUNT)

evh = DirtyHandler()
observer = Observer()
observer.schedule(evh, path=WATCH_PATH, recursive=True)
observer.start()

try:
    while True:
        while evh.dirty:
            time_out = time.time() - evh.dirty_time
            PRINTER_STATUS = ''

            if(os.path.isfile('/home/pi/.usb_share_resources/portal/enable_mono_x')):
                if(os.path.isfile('/home/pi/.usb_share_resources/portal/printer_ip')):
                    HOST = ''
                    PORT = 6000

                    try:
                        with open('/home/pi/.usb_share_resources/portal/printer_ip','r') as f:
                            HOST = f.read().strip()
                            f.close()
                    except:
                        HOST = ''

                    PRINTER_STATUS = ''

                    while PRINTER_STATUS != 'stop':
                        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                        s.settimeout(1)
                        s.connect((HOST,PORT))
                        s.send(b'getstatus,')
                        STATUS = s.recv(1024).decode('UTF-8')
                        arr = STATUS.split(',')
                        PRINTER_STATUS = arr[1].strip()
                        if PRINTER_STATUS.find("stop") >= 0:
                            break
                        else:
                            time.sleep(5)

                    PRINTER_STATUS = ''

            if time_out >= ACT_TIME_OUT and PRINTER_STATUS == '' :
                os.system(CMD_UNMOUNT)
                time.sleep(1)
                os.system(CMD_SYNC)
                time.sleep(1)
                os.system(CMD_MOUNT)
                evh.reset()

            time.sleep(1)

        time.sleep(1)

except KeyboardInterrupt:
    observer.stop()
