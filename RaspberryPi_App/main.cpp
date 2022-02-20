#include "prototypes.h"
#include "functions/functions.cpp"


using namespace std;


int main() 
{
	/* start declare variables*/
	bool successfulInit = true;
	bool angleAchieved;
	float desiredAngle;
	float angle = 0;
	float* anglePtr = &angle;
	usb_relay_device_info* myRelay;
	intptr_t relayDevice;
	imageArr newImg;
	/* END VARIABLES*/


	/* start initialize camera*/
	//The width must be divisible by 320
	//The height must be diviible by 240
	raspicam::RaspiCam Camera; //Camera object
	//getting seg fault for larger values
	Camera.setFormat(raspicam::RASPICAM_FORMAT_RGB); //can change this to bmp
	Camera.setWidth(WIDTH);
	Camera.setHeight(HEIGHT);
	Camera.setBrightness(50);
	Camera.setISO(800);
	Camera.setSharpness(80);

	//Open camera 
	std::cout << "Opening Camera..." << std::endl;
	if(!Camera.open()) 
	{
		successfulInit = false;
		std::cout << "faild to connect to camera!" << std::endl;
	}
	/* END CAMERA INIT*/


	/* start initialize relays*/
	if(usb_relay_init()==-1)
	{
		successfulInit = false;
		std::cout << "failed to connect to relays!" << std::endl;
	}
	else
	{
		myRelay = usb_relay_device_enumerate();
		relayDevice = usb_relay_device_open(myRelay);
		usb_relay_device_open_one_relay_channel(relayDevice, 2);
		usb_relay_device_open_one_relay_channel(relayDevice, 1);
		//usb_relay_device_close_all_relay_channel(relayDevice);
	}
	/* END RELAY INIT*/


	/* start angle adjustments*/
	if(successfulInit)
	{
		do
		{
			newImg.resize(HEIGHT);
			for(int i=0; i < HEIGHT; i++) {newImg[i].resize(WIDTH);};

			/*
			I don't think I need to open and close the socket so frequently
			*/
			//desiredAngle = adjustToAngle();
			desiredAngle = openListenFd();
			
			do
			{
				captureImages(newImg, anglePtr, Camera);
				controlSwitch(angle, desiredAngle, angleAchieved, relayDevice);
			} while (!angleAchieved);
			angleAchieved = false;
		} while(desiredAngle != -1);
	}
	/* END ANGLE ADJUST*/
	

	/* start shut down relay control*/
	usb_relay_device_close_all_relay_channel(relayDevice);
	usb_relay_device_close(relayDevice);
	usb_relay_device_free_enumerate(myRelay);
	usb_relay_exit();
	/* END RELAY SHUTDOWN*/


	/* start shut down camera control*/
	std::cout<<"releasing camera"<<std::endl;
	Camera.release();
	/* END CAMERA SHUTDOWN*/

	return 0;
}