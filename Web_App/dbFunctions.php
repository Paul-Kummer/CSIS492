<?php
	function outputDBToTable(&$outString, $result)
	{
		//create rows to be outputted into a table
		//concatenate the field names
		$outString .= '<table id="ROUTE"><tr>';
		while ($field = mysqli_fetch_field($result))
		{
			$outString = $outString . '<th>' . $field->name . '</th>';
		}
		$outString .=  '</tr>';
		
		//concatenate the data
		while ($row = mysqli_fetch_assoc($result))
		{
			$outString .= '<tr>';
			foreach ($row as $col=>$val)
			{
				$outString =  $outString . '<td>' . $val . '</td>';
			}
			$outString .= '</tr>';
		}
		$outString .= '</table>';
	}

	//will need to run appropriate stored procedure based on selection
	function suUsersTable(&$outString, $result)
	{
		$outString .= '<table id="ROUTE"><tr>';
		while ($field = mysqli_fetch_field($result))
		{
			$outString = $outString . '<th>' . $field->name . '</th>';
		}
		$outString .=  '</tr>';
		
		//concatenate the data
		while ($row = mysqli_fetch_assoc($result))
		{
			$outString .= '<tr>';
			foreach ($row as $col=>$val)
			{
				$outString =  $outString . '<td>' . $val . '</td>';
			}
			$outString .= 
					'<td><form action=".$path." method="POST">
						<input type="text" name="newName" placeholder="new name">
						<input type="submit" class="button" name="suOption" value="Change Name">
						<input type="text" name="newPassword" placeholder="new password">
						<input type="submit" class="button" name="suOption" value="Change Pass">
						<input type="submit" class="button" name="suOption" value="Delete User">
					</form></td>
					</tr>';
		}
		$outString .= 
				'</table>';
	}

	//takes the first two columns and turns the first column into the value and the second column as name
	function makeDropDownOptions($dbQuery, $valID = '0')
	{
		include "dbInfo.php";
		
		$conn = mysqli_connect($host,$username,$password,$dbname);
		$sql;
		$outString;
		
		if ($conn->connect_error) 
		{
			$outString = "!!! ERROR !!!";
			die("!!! Connection failed: " . $conn->connect_error . "!!!");
		}

		else
		{
			switch($dbQuery)
			{
				case "routeList":
					$sql = "call getRouteIDAndName();";
					$result = mysqli_query($conn,$sql);

					while ($row = mysqli_fetch_row($result))
					{
						$outString =  $outString . "<option value='$row[0]'>$row[1]</option>";
					}
				break;

				case "wallList":
				case "userList":
					$sql = "select user_id, user_name from USER order by user_name asc;";
					$result = mysqli_query($conn,$sql);

					while ($row = mysqli_fetch_row($result))
					{
						if($row[0] == $valID && $valID != null)
						{
							//this only works if the page reloads, not sure why
							$outString =  $outString . "<option value='$row[0]' selected>$row[1]</option>";
						}

						else
						{
							$outString =  $outString . "<option value='$row[0]'>$row[1]</option>";
						}
					}
				break;
			}
		}

		$conn->close();
		return $outString;
	}

	//THIS SHOULD BE MOVED TO DB FUNCTIONS
	function executeSP(&$outString, $storedProcedure, $parameter)
	{
		//php file that stores connection info and passwords, stored seperatley so the views don't expose my password
		include "dbInfo.php";

		$conn = mysqli_connect($host,$username,$password,$dbname);
		$sql = '';
		$outString = '';

		//problem with the database connection occured
		if ($conn->connect_error) 
		{
			$outString = "!!! ERROR !!!";
			die("!!! Connection failed: " . $conn->connect_error . "!!!");
		}

		//successfully connected to database
		else
		{
			switch ($storedProcedure)
			{
				//peform a custom query using param input
				case "writeQuery":
					$sql = $parameter;
					$result = mysqli_query($conn,$sql);
					outputDBToTable($outString, $result);
				break;

				//get the hardes route
				case "getMaxClimb":
					$sql = "call $storedProcedure($parameter);";
					$result = mysqli_query($conn,$sql);
					$row = mysqli_fetch_row($result);
					$outString =  "[Name: $row[0] | Difficulty: V$row[1] | Date: $row[2]]";
				break;

				case "validUserName":
				//return the number of climbs
				case "numOfClimbsByUID":
				//return the last session
				case "getLastSessionDate":
				//get a user id number
				case "getUserIdFromName":
				//check if the password is valid
				case "validPassword":
					$sql = "call $storedProcedure($parameter);";
					$result = mysqli_query($conn,$sql);
					$row = mysqli_fetch_row($result);
					$outString =  $row[0];
				break;

				//show everything in the Route table
				case "viewRoute":
					$sql = 
					"select 
					r.rt_id as ID,
					r.rt_name as Name,
					r.rt_diff as 'V Grade',
					u.user_name as Setter,
					r.rt_date as 'Date Set',
					r.rt_info as Information
					from ROUTE r
					left join USER u on r.rt_setter_id = u.user_id
					order by r.rt_diff desc;";
					$result = mysqli_query($conn,$sql);
					outputDBToTable($outString, $result);
				break;
				
				//show the sessions in the SESSION table
				case "viewSession":
					$sql = 
					"select
					u.user_name as Climber,
					r.rt_name as 'Route Name',
					s.sess_date as Date
					from SESSION s
					left join USER u on  u.user_id = s.usr_id
					left join ROUTE r on r.rt_id = s.rt_id
					order by s.sess_date desc;";
					$result = mysqli_query($conn,$sql);
					outputDBToTable($outString, $result);
				break;

				//show the users in the USER table
				case "viewUser":
					$sql = "select user_id, user_name from USER;";
					$result = mysqli_query($conn,$sql);
					outputDBToTable($outString, $result);
				break;

				//call the stored procedure with parameter
				default:
					$sql = "call $storedProcedure($parameter);";
					$result = mysqli_query($conn,$sql);
					outputDBToTable($outString, $result);
				break;
			}
		}
		$conn->close();
	}
?>