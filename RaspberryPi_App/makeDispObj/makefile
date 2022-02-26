#File: makefile
#Name: Paul Kummer
#Program: dispTest
#Modified: 02/21/2022


#Source file of main function entry point
SRC = sources/main.cpp
#What the executable's name will be
EXE = dispTest
#Compiler to Use
CC = g++
#Objects Required for Compilation of Executable
OBJECTS = objects/dispMain.o objects/dispFunctions.o
#Linking Flags, Libraries Needed to be Linked
LFLAGS = -lbcm2835 -lrt -lSSD1306_OLED_RPI -pthread
#Compilation Flags, Options for the compiler
CFLAGS = -Wall -c
#Files that must be included
INCLUDE = 


#SCRIPT START
build: 				clean make_directories $(EXE)

#MAKE DIRECTORIES
make_directories:
	@echo '      [Making Objects Directory]'				
	mkdir -vp objects
	@echo '      [Directory Made]'
	@echo ''

#MAKE EXECUTABLE
$(EXE):						$(OBJECTS)
	@echo '      [Making Executable]'
	$(CC) $(OBJECTS) -o $(EXE) $(LFLAGS)
	@echo '      [Executable Made]'
	@echo ''

#MAKE OBJECT FILES
objects/dispMain.o:			$(SRC) objects
	@echo '      [Making Object File]'
	$(CC) $(CFLAGS) $(SRC) -o objects/dispMain.o
	@echo '      [Object Made]'
	@echo ''

objects/dispFunctions.o:	functions/functions.cpp objects
	@echo '      [Making Object File]'
	$(CC) $(CFLAGS) functions/functions.cpp -o objects/dispFunctions.o
	@echo '      [Object Made]'
	@echo ''

#REMOVE OBJECTS AND EXECUTABLE
clean:
	@echo '      [Removing Objects, Executable, and Temp Files]'
	rm -rf $(EXE) $(OBJECTS) *~
	@echo '      [Clean Complete]'
	@echo ''