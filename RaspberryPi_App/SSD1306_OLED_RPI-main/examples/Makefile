# Gavin lyons 06-2021 
# Makefile to build example program for SSD1306_OLED_RPI library.
# URL: https://github.com/gavinlyonsrepo/SSD1306_OLED_RPI

# ************************************************
# User SRC directory Option Section
# pick on example "SRC" dir and ONE ONLY.
# Comment out the other 5. #

#SRC=src/HELLO_WORLD
#SRC=src/BITMAP
#SRC=src/CLOCK_DEMO
#SRC=src/OLED_FUNCTIONS
#SRC=src/SPEED_TEST
SRC=src/TEXT_GRAPHICS
#SRC=src/HELLO_WORLD_128_32
# ************************************************

CC=g++
LDFLAGS= -lbcm2835 -lrt -lSSD1306_OLED_RPI 
CFLAGS = -Iinclude/ -c -Wall 
MD=mkdir
OBJ=obj
SRCS = $(wildcard $(SRC)/*.cpp)
OBJS = $(patsubst $(SRC)/%.cpp,  $(OBJ)/%.o, $(SRCS))
BIN=./bin
TARGET=$(BIN)/test

all: clean pre-build $(TARGET) 

#Makes the bin and obj directory
pre-build:
	@echo '*******'
	@echo '[START!]'
	$(MD) -vp $(BIN)
	$(MD) -vp $(OBJ)

#Makes the executable in the bin directory
$(TARGET): $(OBJS) $(OBJ)
	@echo '[MAKE EXE FILE!]'
	$(CC)  $(OBJS) -o $@ $(LDFLAGS)
	@echo '[DONE!]'

#Makes object files in the obj directory
#$(SRC) is where the main.cpp resides that is being compiled
$(OBJ)/%.o :  $(SRC)/%.cpp   $(OBJ) 
	@echo '[MAKE OBJECT FILE!]'
	$(CC) $(CFLAGS) $< -o  $@ 

# $@ == $(TARGET) 

#Removes objects and executable
clean:
	@echo '*******'
	@echo '[CLEANUP!]'
	rm -vf $(OBJS) $(TARGET)
	@echo '[DONE!]'
