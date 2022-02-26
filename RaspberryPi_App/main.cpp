#include "prototypes.h"
#include "functions/functions.cpp"

int main() 
{
	/* start declare variables*/
	bool successfulInit = true;
	bool angleAchieved = false;
	float desiredAngle;
	float angle = 0;
	float* anglePtr = &angle;
	usb_relay_device_info* myRelay;
	intptr_t relayDevice;
	imageArr newImg;
	DIRECTION dir = STOP;
	DIRECTION* dirPtr = &dir;
	/* END VARIABLES*/

////// INITIALIZE THE PERIPHERALS //////

	/* Statup OLED Display*/
	std::cout << "Starting OLED Display..." << std::endl;
	if(!bcm2835_init())
	{
		successfulInit = false;
		std::cout << "faild to connect to OLED display!" << std::endl;
	}

	SetupDisp();
	std::thread dispThread (Disp, dirPtr, anglePtr);
	/* END DISP START */

	/* start initialize camera*/
	//The width must be divisible by 320
	//The height must be diviible by 240
	//getting seg fault for larger values
	raspicam::RaspiCam Camera; //Camera object
	Camera.setFormat(raspicam::RASPICAM_FORMAT_RGB); //can change this to bmp
	Camera.setWidth(WIDTH);
	Camera.setHeight(HEIGHT);
	Camera.setBrightness(50);
	Camera.setISO(800);
	Camera.setSharpness(80);

	//Open camera 
	std::cout << "Starting Camera..." << std::endl;
	if(!Camera.open()) 
	{
		successfulInit = false;
		std::cout << "faild to connect to camera!" << std::endl;
	}
	/* END CAMERA INIT*/


	/* start initialize relays*/
	std::cout << "Starting Relays..." << std::endl;
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

////// END INITIALIZATION //////


////// MAIN LOOP OF THE PROGRAM //////

	/* start angle adjustments*/
	if(successfulInit)
	{
		do
		{
			newImg.resize(HEIGHT);
			for(int i=0; i < HEIGHT; i++) {newImg[i].resize(WIDTH);};

			desiredAngle = openListenFd();
			
			do
			{
				captureImages(newImg, anglePtr, Camera);
				controlSwitch(angle, desiredAngle, angleAchieved, relayDevice, dirPtr);
			} while (!angleAchieved && desiredAngle != -1);
			angleAchieved = false;
		} while(desiredAngle != -1);
	}
	/* END ANGLE ADJUST*/
	
	dir = EXIT;
	dispThread.join();

////// END MAIN LOOP //////


////// SHUTDOWN PERIPHERALS //////

	/* shut down disp control*/
	std::cout<<"releasing display"<<std::endl;
	EndDisp();
	/* END DISP SHUTDOWN*/

	/* shut down camera control*/
	std::cout<<"releasing camera"<<std::endl;
	Camera.release();
	/* END CAMERA SHUTDOWN*/

	/* shut down relay control*/
	std::cout<<"releasing relays"<<std::endl;
	usb_relay_device_close_all_relay_channel(relayDevice);
	usb_relay_device_close(relayDevice);
	usb_relay_device_free_enumerate(myRelay);
	usb_relay_exit();
	/* END RELAY SHUTDOWN*/

////// END SHUTDOWN //////

	return 0;
}