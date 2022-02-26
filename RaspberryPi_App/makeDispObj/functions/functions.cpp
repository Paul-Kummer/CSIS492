// INCLUDES
#include <bcm2835.h>
#include "SSD1306_OLED.h"
#include <time.h>
#include <stdio.h>
#include "../prototypes/prototypes.h"

// CONSTANTS
#define myOLEDwidth  128
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
const int XMOVEPEAKPT = ((XMOVEBOXEND - XMOVEBOXSTART) / 2) + XMOVEBOXSTART;

const int WALLANGLECORRECTION = 90;


// The Display Object
SSD1306 myOLED(myOLEDwidth ,myOLEDheight);

// The Travel Direction of the Wall
//enum DIRECTION {DOWN = -1, STOP = 0, UP = 1};


void SetupDisp() 
{
	uint8_t  screenBuffer[myOLEDwidth * (myOLEDheight/8)+1]; 

	bcm2835_delay(500);
	printf("OLED Begin\r\n");
	myOLED.OLEDbegin(); // initialize the OLED
 	myOLED.buffer = (uint8_t*) &screenBuffer;
	myOLED.OLEDclearBuffer();
	myOLED.setTextColor(WHITE);

	myOLED.setFontNum(4);
	myOLED.setTextSize(2);
	myOLED.setCursor(15,4);
	myOLED.print("CLIMB");

	myOLED.setFontNum(1);
	myOLED.setTextSize(1);
	myOLED.setCursor(50,22);
	myOLED.print("with");

	myOLED.setFontNum(4);
	myOLED.setTextSize(2);
	myOLED.setCursor(25,35);
	myOLED.print("LAMP");

	myOLED.setFontNum(1);
	myOLED.setTextSize(1);
	myOLED.setCursor(15,55);
	myOLED.print("By: Paul Kummer");
	myOLED.OLEDupdate();
	bcm2835_delay(5000);
}


void EndDisp()
{
	myOLED.OLEDPowerDown(); //Switch off display
	bcm2835_close(); // Close the library
	printf("OLED End\r\n");
}


void Disp(DIRECTION* dir, float* curAngle)
{
	//uint8_t  screenBuffer[myOLEDwidth * (myOLEDheight/8)+1]; 
	//bcm2835_delay(500);
	bool keepLooping = true;

	// Movement Direction Variables
	int i = 64; // base y coord for movement indicator, actual y coord built from this

	// Status Variables
	char up[] = "Moving Up!";
	char stop[] = "Stopped";
	char down[] = "Moving Down!";
	char angle[2];
	sprintf(angle, "%i", (WALLANGLECORRECTION - (int)(*curAngle)));

	// Define a full screen buffer and struct
	uint8_t  screenBuffer[myOLEDwidth * (myOLEDheight / 8) ];
	myOLED.buffer = (uint8_t*) &screenBuffer;  // set that to library buffer pointer
	myOLED.OLEDclearBuffer(); // clear the buffer
	myOLED.setTextColor(WHITE);

	// myOLED.drawRect(xStart, yStart, rectWidth, rectHeight, color) builds down and right from start
	// myOLED.fillTriangle(xFirst, yFirst, xSecond, ySecond, xThird, yThird, color)
	while(keepLooping)
	{
		switch(*dir)
		{
			case UP:
				// all the draws must go in here because of updates to the buffer with a buffer clear
				while (*dir == UP)
				{
					sprintf(angle, "%i", (WALLANGLECORRECTION - (int)(*curAngle)));
					//printf("UP: %f\n", (WALLANGLECORRECTION - *curAngle));

					// STATUS TEXT
					myOLED.setFontNum(1);
					myOLED.setTextSize(1);
					myOLED.setCursor(10, 8);
					myOLED.print(up);
					
					// CURRENT ANGLE TEXT
					myOLED.setFontNum(2);
					myOLED.setTextSize(4);
					myOLED.setCursor(4,30);
					myOLED.print(angle);

					// POSITION SCALE
					DrawScale(XSCALESTART, YSCALEOFFSET, SCALESIZE, WALLMIN, WALLMAX, (WALLANGLECORRECTION - *curAngle));

					// MOVEMENT AREA
					myOLED.drawRect(XMOVEBOXSTART, YMOVEBOXSTART, XMOVEBOXWIDTH, YMOVEBOXEND, WHITE);					

					// MOVEMENT INDICATOR
					i = i <= 0? 64: (i - 8) % 64; // cylical upwards vertical pos incrementer
					myOLED.fillTriangle(XMOVEBOXSTART, i, XMOVEBOXEND , i, XMOVEPEAKPT, i-16, WHITE);	

					// update buffer and reset it after a delay
					myOLED.OLEDupdate();
					bcm2835_delay(50);
					myOLED.OLEDclearBuffer();
				}
				break;

			case STOP:
				while (*dir == STOP)
				{
					for(int numOfStrobes = 0; (numOfStrobes < 5 && *dir == STOP); numOfStrobes++)
					{
						for (int n = 0; (n <= 16 && *dir == STOP); n++)
						{
							sprintf(angle, "%i", (WALLANGLECORRECTION - (int)(*curAngle)));
							//printf("STOP: %f\n", (WALLANGLECORRECTION - *curAngle));

							// STATUS TEXT
							myOLED.OLEDclearBuffer();
							myOLED.setFontNum(1);
							myOLED.setTextSize(1);
							myOLED.setCursor(15, 8);
							myOLED.print(stop);

							// CURRENT ANGLE TEXT
							myOLED.setFontNum(2);
							myOLED.setTextSize(4);
							myOLED.setCursor(4,30);
							myOLED.print(angle);

							// POSITION SCALE
							DrawScale(XSCALESTART, YSCALEOFFSET, SCALESIZE, WALLMIN, WALLMAX, (WALLANGLECORRECTION - *curAngle));

							// MOVEMENT AREA
							myOLED.drawRect(XMOVEBOXSTART, YMOVEBOXSTART, XMOVEBOXWIDTH, YMOVEBOXEND, WHITE);

							// MOVEMENT INDICATOR
							myOLED.drawRect((XMOVEBOXSTART + n), (24 + n), (16 - (n * 2)), (16 - (n * 2)), WHITE);
							myOLED.OLEDupdate();
							bcm2835_delay(50);
						}
					}

					if(*dir == STOP)
					{
						myOLED.OLEDclearBuffer();
						myOLED.setFontNum(4);
						myOLED.setTextSize(2);
						myOLED.setCursor(15,4);
						myOLED.print("CLIMB");

						myOLED.setFontNum(1);
						myOLED.setTextSize(1);
						myOLED.setCursor(50,22);
						myOLED.print("with");

						myOLED.setFontNum(4);
						myOLED.setTextSize(2);
						myOLED.setCursor(25,35);
						myOLED.print("LAMP");

						myOLED.setFontNum(1);
						myOLED.setTextSize(1);
						myOLED.setCursor(15,55);
						myOLED.print("By: Paul Kummer");
						myOLED.OLEDupdate();
						bcm2835_delay(2500);
					}
				}
				break;

			case DOWN:
				while (*dir == DOWN)
				{
					sprintf(angle, "%i", (WALLANGLECORRECTION - (int)(*curAngle)));
					//("DOWN: %f\n", (WALLANGLECORRECTION - *curAngle));

					// STATUS TEXT
					myOLED.setFontNum(1);
					myOLED.setTextSize(1);
					myOLED.setCursor(2, 8);
					myOLED.print(down);
					
					// CURRENT ANGLE TEXT
					myOLED.setFontNum(2);
					myOLED.setTextSize(4);
					myOLED.setCursor(4,30);
					myOLED.print(angle);

					// POSITION SCALE
					DrawScale(XSCALESTART, YSCALEOFFSET, SCALESIZE, WALLMIN, WALLMAX, (WALLANGLECORRECTION - *curAngle));

					// MOVEMENT AREA
					myOLED.drawRect(XMOVEBOXSTART, YMOVEBOXSTART, XMOVEBOXWIDTH, YMOVEBOXEND, WHITE); 					

					// MOVEMENT INDICATOR
					i = i >= 64? 0: (i + 8) % 64; // cylical upwards vertical pos incrementer
					myOLED.fillTriangle(XMOVEBOXSTART, i, XMOVEBOXEND, i, XMOVEPEAKPT, (i + 16), WHITE);	

					// update buffer and reset it after a delay
					myOLED.OLEDupdate();
					bcm2835_delay(50);
					myOLED.OLEDclearBuffer();
				}
				break;
			
			case EXIT:
				keepLooping = false;
				break;
		};
	}
}

// The Scale is built downward from start
void DrawScale(int xStart, int yOffset, int scaleSize, int scaleMin, int scaleMax, float curPos)
{
	// Scale Variables
	int yPosBase; // base y coord for scale indicator, actual y coord built from 
	char min[2]; // increase this size for values greater than two places
	char max[2]; // increase this size for values greater than two places
	sprintf(min, "%i", scaleMin);
	sprintf(max, "%i", scaleMax);

	// ANGLE POSITION SCALE
	myOLED.setFontNum(1);
	myOLED.setTextSize(1);
	// Top of Scale Text
	myOLED.setCursor(xStart, (yOffset - 12)); 
	myOLED.print(min);
	// Vertical Line
	myOLED.drawLine	(xStart,		(yOffset),
					(xStart),		(yOffset + scaleSize),				WHITE);
	// Top Line
	myOLED.drawLine	(xStart,		(yOffset),
					(xStart + 4),	(yOffset),							WHITE);
	// 1/4 Mark
	myOLED.drawLine	(xStart,		(yOffset + (scaleSize / 4)),
					(xStart + 2),	(yOffset + (scaleSize / 4)), 		WHITE); 
	// 1/2 Mark
	myOLED.drawLine	(xStart,		(yOffset + (scaleSize / 2)),
					(xStart + 4), 	(yOffset + (scaleSize / 2)), 		WHITE);
	// 3/4 Mark
	myOLED.drawLine	(xStart,		(yOffset + ((scaleSize * 3) / 4)),
					(xStart + 2), 	(yOffset + ((scaleSize * 3) / 4)),	WHITE);
	// Bottom Line
	myOLED.drawLine	(xStart,		(yOffset + scaleSize),
					(xStart + 4),	(yOffset + scaleSize),				 WHITE);
	// Bottom of Scale Text
	myOLED.setCursor(xStart,		(scaleSize + yOffset + 4)); 
	myOLED.print(max);

	// POSITION INDICATOR
	/*
	first: make a ratio of what percentage of the max angle is the wall at.
	second: multiply that ratio by the size of the scale.
	third: add the offset to make zero the starting point of the y coord
	*/
	yPosBase = (int)((curPos / (float)(scaleMax - scaleMin)) * scaleSize) + (yOffset - 3); // subtract from offset for minor adjustments
	myOLED.fillTriangle((xStart - 8), yPosBase, (xStart - 8), (yPosBase + 6), xStart, (yPosBase + 3), WHITE);
}