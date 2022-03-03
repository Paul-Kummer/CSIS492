File: README.txt
Author: Paul Kummer
Project: CLIMB
Modified: 03/02/22


	            -{ ROOT README }-

	Climbing Log Information Managment Bundle (CLIMB)
	                  with
	  Linux, Apache, MySQL, and PHP (LAMP)


	-[Description]-
    CLIMB is a compilation of code that is devloped by the author, from
public repositories, and from web tutorials. The completed project is a
web interface that allows users to login into a webpage to track their climbs and
manage climbs that are availabe. There is also support for a Raspberry Pi to control
a USB relay based off the angle measurment of a camera image and output the status
to an OLED display.

    For this project to work, a LAMP stack must be setup on a computer, or you must have 
access to a MySQL database, Apache webserver, and a PHP server. There are other
variations that could be done, but this project was done exclusively on a LAMP stack.


	-[Contents] (file type : name: description)-
Directory 	: RaspberryPi_App 	: This folder contains the CLIMB portion of the project

Directory 	: Web_App 		: This folder has files used in conjuntion with the LAMP stack

File 		: README.txt		: The file you're currently reading, containing general information
					  of what this project is about and how to use it.


	-[Physical Requirments]-
1.) Computer capable of hosting a LAMP stack (Below are the Specs of the Server Used in the Project)
		Dell Latitude Laptop
	a.) OS: 	Linux Mint 18.2 Cinnamon 64-bit
	b.) Kernal: 	4.8.0-53 generic
	c.) Processor: 	Intel Core i5-24100M CPU @ 2.30GHz x 2
	d.) Memory: 	6GB
	e.) HDD: 	301.4GB
	f.) GPU: 	Integrated
2.) Wireless Router: 		D-Link Dir 615 with DDWRT firmware (A wired network can also work, but it was not 
				tested or used in the project)
3.) Raspberry Pi Zero W: 	Vilros Raspberry Pi Zero W Basic Starter Kit- Black Case Edition-Includes Pi Zero W
    				-Power Supply & Premium Black Case (Other Raspberry Pis should work, but they were 
				not tested)
	a.) USB Relay: 		Comimark USB Control 2-Way 5V 2 Channel Relay Module Computer Control PC Intelligent
	    			(There must be at least two relays, this project has two relays on one PCB)
	b.) Camera: 		Dorhea for Raspberry Pi 4 Model B Mini Camera Video Module 5MP 1080P Sensor OV5647 
				(I had to adjust the focal length by screwing the camera lens either in or out)
	c.) OLED Display: 	IZOKEE 0.96'' I2C IIC 12864 128X64 Pixel OLED LCD Display Shield Board Module 4 Pin
4.) Adjustable Climbing Wall (Any device that is operated with a 3 position switch ON/OFF/ON will work)
5.) A mostly white image with a black line indicating the angle (the size of the image is dependent on the camera settings)
	a.) Must be able to move with the object being measured
	b.) Must be well lit and may require lighting



	-[Design]-
    This project is has two major parts. The CLIMB portion deals with the controlling the Raspberry Pi computer
and the LAMP portion deals with the webserver and database. Each of the major portions is contained to its own
directory and is broken down into other sections.

I. CLIMB
    In the CLIMB's directory there are three components that need to function independently before creating the
main line of logic in this project. The camera on the Raspberry Pi must be setup and functioning using the provided
API. This will allow the Raspberry Pi to capture images and analyze them to determine the angle of the object using
linear regression. Next, the USB relays must be setup and functioning with the provided API. With these relays, the
Raspberry Pi will be able to send a signal to them to toggle there status to open or closed, which will complete a
circuit that will move the climbing wall up or down. Finally, the OLED display needs to have its provided API setup.
This API will allow the Raspberry Pi to output the status of the climbing wall to the user. It will be able to tell
the user what the angle of the wall along with if the wall is moving up, stopped, or moving down. After all the APIs
are working correctly, then any shared libraries can be moved to appropriate directories and the CLIMB's makefile can 
be executed. 

    The CLIMB's main program will initialze its peripherals and then continue looping, awaiting an TCP message
containing an angle for the climbing wall to be adjusted. Whenver a message is recieved, after ensuresing the angle is
valid, the camera will begin taking pictures. After each picture is taken, the image is resized, scrubbed of
unimportant pixels, changed to black and white, each remaining pixel is put into a linear regression algorithm, and a
line of best fit is drawn onto the image. Within that process, a floating point number is output indicating the angle
of the climbing wall. This angle is used in another part of the program to determine what USB relay to turn on or off,
depending if the climbing wall should move up or down. Once the angle captured by the camera is within two degrees of
the requested angle, the USB relays will switch to a state where the climbing wall will stop moving. After the desired
angle is achieved, the main line of logic will go back to listening for a new message. If the message is ever 91, the
program will begin its shutdown sequence by freeing resources to the peripherals and turning them to an off state. During
this entire process, the OLED display will output the climbing wall's status.

    Since the OLED display would only get updated whenver a new message is recieved over the TCP connection if it was ran on 
the main thread, it is instead put into its own thread. This allows the OLED display to be updated more frequently and 
it can also access variables within the parent process, giving it current information. This cannot be done if the process 
is forked because the OLED would not be able to recieve updated information from its parent process since variables are
not shared among processes.

II. LAMP
    The purpose of the LAMP stack is to provide a user interface allowing the user to manage their sessions and interact
with their climbing enviorment throug the Raspberry Pi. The server's operating system is a Linux build. This is free and
allows the use of other requirements to complete this project. On the Linux computer there is installed servers to support
a MySQL database server, Apache Webserver, and a PHP server. The MySQL database will be used to store information about the
routes and user. It will also be able to comunicate this information to the PHP server. That way when a user accesses the index
file through the Apache server, the PHP server will processes the file to create the appropriate HTML file that the user
is looking for. When the PHP file is executed it may create a TCP connection with the Raspberry Pi to send a message of
and angle that the wall should be adjusted or the MySQL database will be queried with a stored procedure. When the stored
procedure is called, it may transmit back table data that will then be display to the user or simply update a table.


	-[Setup]-
I. SETUP CLIMB (RaspberryPi_App)
    In the current version of this project, there are multiple make files that must be executed in a specific
order before making the final executable. First, the APIs must have their make files ran. The APIs
include raspicam-master, HIDUSBRelay, and SSD1306_OLED_RPI-main. Some APIs may have dependencies that you
must install before you can run their Make files. Read the APIs' READMEs carefully and follow their instructions.
Also, I have provided my own READMEs to aid in completeing the required steps. These APIs must be compiled first
because they contain libraries that are needed in compiling the executable "adjustAngle".

II. SETUP LAMP (Web_App) *LINUX ONLY*
    First, install the LAMP stack if you haven't already. There are numerous tutorials on how to do this. Then,
setup the MySQL database to work on your network. Try to create a simple table and add some entries. Next, ensure
that you can query the database and retreive information from the table. If the database is functioning, then the
stored procedures, functions, and tables can be added to the database from the provided files. Note, some files
may have to be modified to point to your specific database and credentials may need to be provided. Some of the
database files may contain some test data or data from a running state like the database dump.

    Setup the Apache and PHP server. If you followed the tutorials on setting up a LAMP stack, you should be
able to reach the default Apache page that is created on your computer. This is typically in the directory
"/var/www/html". The webserver will automatically use the index file as a default, so you should be able to just
copy the PHP files into that directory for the server to find them. However, you will have to modify the files to
use the database you just created. Also, depending on your network, you may have to open ports or create rules on
your firewall if you have one.


	-[To-Do]-
1. Make sure all non-original code is cited
	a. Review the codes licensing to make sure it is followed
2. Complete READMEs for different portions of the project
3. Orgainize Files into logical groupings
	a. Update the make files to work with the new locations
	b. Update the code to work with the new locations
4. Check angle algorithm for improvements
5. Look into a way of stopping movement after it is in motion
6. Update User interfaces to make them better looking
7. Add in some features that are partially developed
	a. different priveledge levels for accounts. (admin, manager, user)
8. Attempt to create one make file for compiling everything in the project


	-[Bugs]-



	-[Sources]-

