
// Example file name : main.cpp
// Description:
// Test file for SSD1306_OLED library, showing  hello world
// URL: https://github.com/gavinlyonsrepo/SSD1306_OLED_RPI
// *****************************

#include "../prototypes/prototypes.h"
#include <stdio.h>
#include <bcm2835.h>
#include <iostream>
#include <thread>
#include <sys/time.h>
#include <unistd.h>

int main(int argc, char **argv) 
{
	DIRECTION dir = STOP;
	DIRECTION* dirPtr = &dir;
	float angle = 0;
	float* anglePtr = &angle;
	unsigned int quarterSecond = 250000; //1s == 1000000ms

	/* Statup OLED Display */
	std::cout << "Starting OLED Display..." << std::endl;
	if(!bcm2835_init())
	{
		std::cout << "faild to connect to OLED display!" << std::endl;
	}
	SetupDisp();
	/* END DISP START */

    std::thread dispThread (Disp, dirPtr, anglePtr);
	
	std::cout << "Moving Down..." << std::endl;
	while(angle < 60)
	{
		dir = DOWN;
		usleep(quarterSecond);
		angle++;
	}

	std::cout << "Stopped..." << std::endl;
	dir = STOP;
	usleep(24 * quarterSecond);

	std::cout << "Moving Up..." << std::endl;
	while(angle > 0)
	{
		dir = UP;
		usleep(quarterSecond);
		angle--;
	}
	std::cout << "Stopped..." << std::endl;
	dir = STOP;

	usleep(12 * quarterSecond);
	std::cout << "[ EXIT ]" << std::endl;
	dir = EXIT;

	dispThread.join();
	
	EndDisp();

	return 0;
}