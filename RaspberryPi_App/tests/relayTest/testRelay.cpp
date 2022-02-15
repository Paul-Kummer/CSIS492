#include <iostream>
#include <unistd.h>
#include "usb_relay_device.h"
using namespace std;

int main()
{
	int sleepTimeMicroSec = 1000000; //1 second
	int returnVal = 0;
	if(usb_relay_init()==-1)
	{
		std::cout << "failed" << std::endl;
	}

	usb_relay_device_info* myRelay = usb_relay_device_enumerate();
	int hd = usb_relay_device_open(myRelay);

	int ct = 0;
	while(ct < 100)
	{
		ct++;
		if(ct%2 == 0)
		{
			returnVal = usb_relay_device_open_one_relay_channel(hd, 1);
			returnVal = usb_relay_device_close_one_relay_channel(hd, 2);
		}
		else
		{
			returnVal = usb_relay_device_open_one_relay_channel(hd, 2);
			returnVal = usb_relay_device_close_one_relay_channel(hd, 1);
		}
		usleep(sleepTimeMicroSec/((ct+1)*0.1));
	}
	returnVal = usb_relay_device_close_all_relay_channel(hd);	
	usb_relay_device_close(hd);
	usb_relay_exit();
	return returnVal;
}