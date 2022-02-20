<?php
	$MAXANGLE = 90;
	$MINANGLE = 30;
	$ADDRESS = '192.168.1.82';
	$PORT = '8080';

	function ToggleBool(&$boolVal)
	{
		//$boolVal = $boolVal?false:true;
		if($boolVal)
		{
			$boolVal = false;
		}
		else
		{
			$boolVal = true;
		};
	};


	function AdjustAngle($newAngle)
	{
		global $ADDRESS, $PORT;
		$isSuccess = false;

		if(ValidateAngle($newAngle))
		{
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

			if($socket)
			{
				$connect = socket_connect($socket, $ADDRESS, $PORT);

				if($connect)
				{
					$isSuccess = socket_write($socket, $newAngle, strlen($newAngle));
				};
			};

			socket_close($socket);
		};

		return $isSuccess;
	};
	

	function ValidateAngle($angle)
	{
		global $MAXANGLE, $MINANGLE;
		$isValid = false;

		if(is_numeric($angle))
		{
			/*
			Climbers view a wall of straight vertical as a 0 degree wall. A Horizontal
			wall would be considered 90 degrees. This is 90 degree difference geometrically
			so it must be adjusted by subtracting the $angle from 90 degrees.
			*/
			$angle = ((int)90 - (int)$angle);

			//Make sure the angle is within the walls valid range
			if($angle <= $MAXANGLE && $angle >= $MINANGLE)
			{
				$isValid = true;
			};
		};

		return $isValid;
	};
?>