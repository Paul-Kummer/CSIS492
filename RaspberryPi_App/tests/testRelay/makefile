#File: makefile
#Name: Paul Kummer
#Program: testRelay.exe
#Modified: 05/16/21


EXE = testRelay
CC = g++ -std=c++11
REMOVABLE_OBJS = testRelay.o
PERM_OBJS = 
OBJECTS = $(REMOVABLE_OBJS) $(PERM_OBJS)
LFLAGS = -l:usb_relay_device.so
CFLAGS = -Wall -I/home/pi/Desktop/relayTest -Wextra -pedantic -g #--verbose


$(EXE):							$(OBJECTS)
									$(CC) $(CFLAGS) -o $(EXE) $(OBJECTS) $(LFLAGS)

testRelay.o:					usb_relay_device.h
									$(CC) $(CFLAGS) -c testRelay.cpp

clean:
									rm -rf $(EXE) $(REMOVABLE_OBJS) *~
