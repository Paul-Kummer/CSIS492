<?php
	//putting include here will preserve class objects if they are ever used
	include "dbFunctions.php";
	include "functions.php";
	include "forms.php"; 
	session_start() 
?>

<!DOCTYPE PHP>

<?php
	extract($_SESSION);
	extract($_REQUEST);

	$path = "index.php";
	$_SESSION['toggleVal'] = $toggle; //switches dark mode, show index source, or show function source
	$_SESSION['data'] = $data; //holds data from custom quries
	$_SESSION['dispOut'] = ""; //data that gets outputed from query results
	//$_SESSION['form'] = $loginForm; //the form that will be displayed to the user
	$_SESSION['Submit'] = $Submit; //value of submit button pressed
	$noToggle = false; //test if there was a toggle button pressed
	$noSubmit = false; //test if there was a submit button pressed
	$notification = "-Ready To Start-"; //text to display what is happening to user




	//check for toggling views and switch bool if selected
	switch ($_SESSION['toggleVal'])
	{
		case "Dark Mode":
			ToggleBool($_SESSION['dMode']);
			$notification = "-Toggle Dark Mode-";
			$uName = $_SESSION['uName'];
		break;

		default:
			$noToggle = true;
		break;
	}




	//actions based on buttons with name = "Submit"
	switch ($_SESSION['Submit'])
	{
		//users main page of interaction after successfully logging in
		case "Login":
			$paramString = "'".$_REQUEST['uName']."','".$_REQUEST['uPass']."'";
			$correctPass;
			executeSP($correctPass, "validPassword", $paramString);
			
			if($correctPass == "true" || $_SESSION['Authenticated'])
			{
				//if user has default route
				$_SESSION['Authenticated'] = true;
				if($noToggle)
				{
					$_SESSION['uName'] = $_REQUEST['uName'];
				}

				$_SESSION['uID'];
				executeSP($_SESSION['uID'], "getUserIdFromName", ("'".$_SESSION['uName'])."'");

				$_SESSION['totalClimbs'];
				$_SESSION['hardestClimb'];
				$_SESSION['lastClimbDate'];
				executeSP($_SESSION['totalClimbs'], "numOfClimbsByUID", $_SESSION['uID']);
				executeSP($_SESSION['hardestClimb'], "getMaxClimb", $_SESSION['uID']);
				executeSP($_SESSION['lastClimbDate'], "getLastSessionDate", $_SESSION['uID']);
				
				$notification = "User: ".$_SESSION['uName']."|ID: ".$_SESSION['uID']."  -logged on-";
				$_SESSION['form'] = getForm("userLanding");

				//else load wall selection form
			}
			
			else
			{
				$_SESSION['form'] = getForm("loginForm");
				$notification = "-Incorrect Password-";
			}

		break;

		//checks if the create user password was entered correct then sends them to login page
		case "Create":
			//$uName = $_REQUEST['uName']; //change the variable to session?
			//$uPass = $_REQUEST['uPass'];
			$uPassTwo = $_REQUEST['uPassTwo'];
			$paramString = "'".$_REQUEST['uName']."','".$_REQUEST['uPass']."'";
			$validUName;
			executeSP($validUName, "validUserName", "'".$_REQUEST['uName']."'");
			
			if ($uPass == $uPassTwo && $validUName)
			{
				$notification = "-User [$uName] Created-";
				executeSP($_SESSION['dispOut'], "sp_makeUser", $paramString);
				$_SESSION['Submit'] = '';
				$_SESSION['form'] = getForm("loginForm");
			}

			elseif(!$validUName)
			{
				$notification = "-The User Is Unavailable-";
			}

			elseif($uPass != $uPassTwo)
			{
				$notification = "-Passwords Do Not Match-";
			}

			else
			{
				$notification = "-An Unforseen Problem Occured-";
			}
		break;

		//page for making a new user
		case "New User":
			$_SESSION['uName'] = $_REQUEST['uName'];
			$_SESSION['uPass'] = $_REQUEST['uPass'];
			$_SESSION['form'] = getForm("createUserForm");
			$notification = "-Creating New User-";
		break;

		// START HERE
		//
		case "View User Sessions":
			//call getSessionsByUser('1');
			$paramString = "'".$_SESSION['uID']."'";
			executeSP($_SESSION['dispOut'], "getSessionsByUser", $paramString);
			$notification = "-Showing User Sessions-";
		break;


		case "Find Route":
			//call viewRtsBetween('0', '5');
			$paramString = "'".$_REQUEST['minGrade']."','".$_REQUEST['maxGrade']."'";
			executeSP($_SESSION['dispOut'], "viewRtsBetween", $paramString);
			$notification = "-Showing Routes Within Range-";
		break;


		case "Add Session":
			//call addSession('uID', 'rtID', 'Test This climb was hard');
			$paramString = "'".$_REQUEST['userIDSelect']."','".$_REQUEST['routeIdSelect']."','".$_REQUEST['sessInfo']."'";
			executeSP($_SESSION['dispOut'], "addSession", $paramString);
			$notification = "-Added Session-";
		break;
			
		
		case "Add Route":
			//call addRoute(rtName, rtDiff, UID, rtInfo));
			$paramString = "'".$_REQUEST['routeName']."','".$_REQUEST['routeDiff']."','".$_REQUEST['setterID']."','".$_REQUEST['routeInfo']."'";
			executeSP($_SESSION['dispOut'], "addRoute", $paramString);
			$notification = "-Added Route-";
		break;


		case "Change Route Info":
			//call changeRtInfo('1', 'I changed this');
			$paramString = "'".$_REQUEST['routeName']."','".$_REQUEST['routeInfo']."'";
			executeSP($_SESSION['dispOut'], "changeRtInfo", $paramString);
			$notification = "-Changed Route Information-";
		break;


		case "Change Route Grade":
			//call changeRtGrade('1', '6');
			$paramString = "'".$_REQUEST['routeName']."','".$_REQUEST['routeDiff']."'";
			executeSP($_SESSION['dispOut'], "changeRtGrade", $paramString);
			$notification = "-Changed Route Grade-";
		break;


		case "Change Password":
			//call updatePass('uID', 'oldPass', 'newPass');
			$paramString = "'".$_SESSION['uID']."','".$_REQUEST['oldPass']."','".$_REQUEST['newPass']."'";
			executeSP($_SESSION['dispOut'], "updatePass", $paramString);
			$notification = "-Changed Password-";
		break;
		
		
		case "Change Angle":
		//change the climbing walls angle;
		$paramString = $_REQUEST['wallAngle'];
		$adjustSuccess = AdjustAngle($_REQUEST['wallAngle']);
		if($adjustSuccess)
		{
			$notification = "-Changing Wall Angle to [ ". $paramString ." ]-";
		}
		else
		{
			$notification = "-Could Not Change Wall Angle-";
		}
		break;
		// END HERE


		//send query "data;" !!!NOT USED!!!
		case "Execute Query":
			executeSP($_SESSION['dispOut'], "writeQuery", $_SESSION['data']);
			$notification = "-Executing Custom Query-";
		break;

		/* THESE ARE BUTTONS IN THE NAVLINKS */
		//view the ROUTE table
		case "View Routes":
			executeSP($_SESSION['dispOut'], "viewRoute", "null");
			$notification = "-Showing Routes-";
		break;
		
		//view the USER table
		case "View Users":
			executeSP($_SESSION['dispOut'], "viewUser", "null");
			$notification = "-Showing Users-";
		break;
				
		//view the SESSION table
		case "View Sessions":
			executeSP($_SESSION['dispOut'], "viewSession", "null");
			$notification = "-Showing Sessions-";
		break;

		//clear the session and start a new session
		case "Clear":
			session_Destroy();
			session_unset();
			$_SESSION['form'] = getForm("loginForm");
			$notification = "-Cleared Session, New Session Started-";
		break;

		//return to default form
		case "Main Index":
		case "Back":
			$_SESSION['Authenticated'] = false;
			$notification = "-Going Back-";
			$_SESSION['form'] = getForm("loginForm");
		break;

		//no submit was performed
		default:
			$noSubmit = true;
		break;
	};




	//no form was generated or button clicked. default values
	if($noToggle && $noSubmit)
	{
		$_SESSION['form'] = getForm("loginForm");
		$notification = "-Awaiting Input-";
	}




	//turn on dark mode if the button is clicked
	if ($_SESSION['dMode'])
	{
		$styleSheet = "dark.css";
	}

	else
	{
		$styleSheet = "light.css";
	}
	
?>











<!-- HTML CODE BEGINS HERE -->
<html>
   <head>
	  <title>Time II Climb</title>
	  <link rel="stylesheet" type="text/css" href=<?php echo $styleSheet ?>>
   </head>

	<!-- this is where the action notifications go -->
   <div class="header">
		<h2>
			<?php
				echo $notification;
			?>
		</h2>
	</div>

   <body>
	   
	<!-- this is where the generated forms go -->
	<div class="row">
		<div class="selectBox">
			<?php
				echo $_SESSION['form'];
			?>		
		</div>
	</div>

	<!-- this is where permanent navigaiton links are -->
	<div class="row">
		<?php
			//getNavLinks
		?>
		<div class="navLinks">
			<form action=<?php echo $path?> method="POST">
				<!--<button type="navButton" formaction="index.php">Main Index</button> Unused-->
				<input type="submit" class="button" name="Submit" value="Main Index">
				<input type="submit" class="button" name="toggle" value="Dark Mode">
				<!--<input type="submit" class="button" name="Submit" value="View Routes">--> <!--remove this for regular users-->
				<!--<input type="submit" class="button" name="Submit" value="View Users">--> <!--remove this for regular users-->
				<!--<input type="submit" class="button" name="Submit" value="View Sessions">--> <!--remove this for regular users-->
				<input type="submit" class="button" name="Submit" value="Clear">
			</form>
		</div>
	</div>

	<!-- this will be the output of database goes -->
	<div class="row">
		<div class="columnHistory"> 
			<?php
				echo $_SESSION['dispOut'];
			?>
		</div>
	</div>
   </body>
</html>
