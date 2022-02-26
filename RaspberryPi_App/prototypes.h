#ifndef PROTOTYPES
#define PROTOTYPES

#include <stdio.h>
#include <string> //This may cause problem with oleddisp
#include <bitset>
#include <vector>
#include <math.h>
#include <utility>
#include <ctime>
#include <fstream>
#include <iostream>
#include <unistd.h>
#include <thread>

// Raspberry Pi Camera
#include "raspicam-master/src/raspicam.h"
#include "raspicam-master/src/raspicamtypes.h"
#include "bmp.h"
#include "usb_relay_device.h"

// TCP Socket
#include <sys/socket.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <sys/types.h>
#include <netinet/tcp.h>
#include <arpa/inet.h>

// Raspberry Pi OLED Display
#include "bcm2835.h"
#include "SSD1306_OLED.h"
#include <time.h>


// IMAGE AND PROGRAM CONTROL CONSTANTS
const int MAX_ANGLE = 88;
const int MIN_ANGLE = 25;
const int BYTES_PER_PIXEL = 3; // red, green, & blue
const int FILE_HEADER_SIZE = 14;
const int INFO_HEADER_SIZE = 40;

// CAMERA CONSTANTS
const int WIDTH = 640; //must be divisible 320
const int HEIGHT = 720; //must be divisible 240

// TCP CONSTANTS
const std::string ADDRESS = "192.168.1.37"; // webserver IP
const int PORT = 8080;

// DISPLAY CONSTANTS
/*#define myOLEDwidth  128
#define myOLEDheight 64
const int WALLMAX = 60;
const int WALLMIN = 0;
const int XSCALESTART = 90;
const int YSCALEOFFSET = 12;
const int SCALESIZE = 40;
const int XMOVEBOXSTART = 104;
const int YMOVEBOXSTART = 0;
const int XMOVEBOXWIDTH = 16;
const int YMOVEBOXEND = 64;
const int XMOVEBOXEND = XMOVEBOXSTART + XMOVEBOXWIDTH; //WIDTH
const int XMOVEPEAKPT = ((XMOVEBOXEND - XMOVEBOXSTART) / 2) + XMOVEBOXSTART;*/


//This typedef must come after Pixel is defined
typedef std::vector<std::vector<Pixel>> imageArr;

// The Display Object
//SSD1306 myOLED(myOLEDwidth ,myOLEDheight);

// The Travel Direction of the Wall
enum DIRECTION {DOWN = -1, STOP = 0, UP = 1, EXIT = 99};


// IMAGE AND PROGRAM CONTROL
float adjustToAngle();
float getAngle (imageArr& pixArr, bool drawLine=true, bool showStats=true);
unsigned char* createBitmapFileHeader(int stride);
unsigned char* createBitmapInfoHeader();
void generateBitmapImage(unsigned char* image, char* imageFileName);
void toBlackAndWhite(imageArr& pixArr, int maxColorLimit=35, int trimAmount=10);
void makeLine(imageArr& pixArr, float yIntercept, float slope, Pixel lineColor, int lineSize=5);
void makeImage (char* imageName, imageArr pixArr);

// CAMERA
void captureImages (imageArr bmpPixels, float* angle, raspicam::RaspiCam& Camera);

// RELAYS
void controlSwitch(float curAngle, float desiredAngle, bool& angleAchieved, intptr_t& relayDevice, DIRECTION* dirPtr);

// TCP CONNECTION
float openListenFd();

// DISPLAY FUNCTIONS
void SetupDisp(void);
void EndDisp(void);
void Disp(DIRECTION*, float*);
void DrawScale(int, int, int, int, int, float);

#endif