#ifndef PROTOTYPES
#define PROTOTYPES

#include <stdio.h>
#include <string>
#include <bitset>
#include <vector>
#include <math.h>
#include <utility>
#include <ctime>
#include <fstream>
#include <iostream>
#include <unistd.h>
#include "raspicam-master/src/raspicam.h"
#include "raspicam-master/src/raspicamtypes.h"
#include "bmp.h"

const int BYTES_PER_PIXEL = 3; /// red, green, & blue
const int FILE_HEADER_SIZE = 14;
const int INFO_HEADER_SIZE = 40;
const int WIDTH = 320; //must be divisible 320
const int HEIGHT = 480; //must be divisible 240


//This typedef must come after Pixel is defined
typedef std::vector<std::vector<Pixel>> imageArr;

void generateBitmapImage(unsigned char* image, char* imageFileName);
unsigned char* createBitmapFileHeader(int stride);
unsigned char* createBitmapInfoHeader();
void makeImage (char* imageName, imageArr pixArr);
float getAngle (imageArr& pixArr, bool drawLine=true, bool showStats=true);
void toBlackAndWhite(imageArr& pixArr, int maxColorLimit=75, int trimAmount=10);
void makeLine(imageArr& pixArr, float yIntercept, float slope, Pixel lineColor, int lineSize=5);
void controlSwitch(float curAngle, float desiredAngle, bool& angleAchieved); //originally for single thread design
void captureImages (imageArr bmpPixels, float* angle);
float adjustToAngle();

#endif