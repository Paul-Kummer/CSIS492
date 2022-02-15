<?php
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


	function AdjustAngle(&$newAngle, $address='192.168.1.37', $port='8080')
	{
		$succeeded = false;

		if(ValidateAngle($newAngle))
		{
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			$result = socket_connect($socket, $address, $service_port);
	
			if($socket === true && $result === true)
			{
				$succeeded = true;
				socket_write($socket, $newAngle, strlen($newAngle));
			};
	
			socket_close($socket);
		};

		return $succeeded;
	};

	
	function ValidateAngle($angle=null)
	{
		$isValid = false;
		$maxAngle = 90;
		$minAngle = 30;

		if(is_int($angle))
		{
			if($angle <= $maxAngle && $angle >= $minAngle)
			{
				$isValid = true;
			};
		};

		return $isValid;
	};
?>