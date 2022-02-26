//File: prototypes.h
//Name: Paul Kummer
//Program: dispTest
//Modified: 02/21/2022

#pragma once

// The Travel Direction of the Wall
enum DIRECTION {DOWN = -1, STOP = 0, UP = 1, EXIT = 99};

// FUNCTION PROTOTYPES
void SetupDisp(void);
void EndDisp(void);
void Disp(DIRECTION*, float*);
void DrawScale(int, int, int, int, int, float);