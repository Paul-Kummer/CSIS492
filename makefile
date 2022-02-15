#File: testbmp
#Name: Paul Kummer
#Program: getCapture
#Modified: 03/13/2
EXE = adjustAngle
CC = g++ -std=c++11
REMOVABLE_OBJS = main.o #functions.o
PERM_OBJS = 
OBJECTS = $(REMOVABLE_OBJS) $(PERM_OBJS)
LFLAGS = -I/usr/local/include -L/user/local/lib/ -lraspicam -lmmal -lmmal_core -lmmal_util -l:usb_relay_device.so
#LFLAGS = -I/usr/local/include/ -lraspicam -lraspicam_cv -lmmal -lmmal_core -lmmal_util -lopencv_core -lopencv_highgui #this is with opencv
CFLAGS = -Wall -Wextra -pedantic -g

$(EXE):							$(OBJECTS)
									$(CC) $(CFLAGS) -o $(EXE) $(OBJECTS) $(LFLAGS)

main.o:							main.cpp prototypes.h
									$(CC) $(CFLAGS) -c main.cpp

#functions.o:					functions/functions.cpp prototypes.h
#									$(CC) $(CFLAGS) -c functions/functions.cpp

clean:
									rm -rf $(EXE) $(REMOVABLE_OBJS) *~

#do this before make 
#export LIBRARY_PATH=/opt/vc/lib