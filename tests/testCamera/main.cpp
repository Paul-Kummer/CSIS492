	#include "prototypes.h"
	#include "functions/functions.cpp"

	using namespace std;
	 
	int main () 
	{
		bool angleAchieved;
		float desiredAngle;
		float angle = 0;
		float* anglePtr = &angle;


		imageArr newImg;
		newImg.resize(HEIGHT);
		for(int i=0; i < HEIGHT; i++) {newImg[i].resize(WIDTH);};

		desiredAngle = adjustToAngle();
		
		do
		{
			captureImages(newImg, anglePtr);
			controlSwitch(angle, desiredAngle, angleAchieved);
		} while (!angleAchieved);
		
		

		return 0;
	}