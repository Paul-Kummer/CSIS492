<?php
function getForm($formToGet)
{
    $path = "index.php";
    $uName = $_SESSION['uName'];
    $uPass = $_SESSION['uPass'];

    switch($formToGet)
    {
        //let the user log in
        case "loginForm":
            $loginForm = <<< HERE
                <form action=$path method="POST">
                    <label for="uName">User Name:</label>
                    <input type="text" name="uName" value="$uName" placeholder="User Name or ID">
                    <label for="uPass">Password:</label>
                    <input type="password" name="uPass" value="$uPass" placeholder="Enter Password">
                    <br><br><input type="submit" class="button" name="Submit" value="Login">
                    <input type="submit" class="button" name="Submit" value="New User">
                </form>
HERE;

        return $loginForm;

        //create a new user
        case "createUserForm":
            $creatUserForm = <<< HERE
                <form action=$path method="POST">
                    <label for="uName">User Name:</label>
                    <input type="text" name="uName" value="$uName" placeholder="Enter User Name">
                    <label for="uPass">Enter Password:</label>
                    <input type="password" name="uPass" value="$uPass" placeholder="Enter Password">
                    <label for="uPassTwo">Verify Password:</label>
                    <input type="password" name="uPassTwo" placeholder="Re-Enter Password">
                    <br><br><input type="submit" class="button" name="Submit" value="Create">
                    <input type="submit" class="button" name="Submit" value="Back">
                </form>
HERE;

        return $creatUserForm;      

        //where the user goes after logging in
        case "userLanding":
            $userLanding =
                '<p>' .
                        '------{CURRENT USER: '.$_SESSION['uName']." | ID: ".$_SESSION['uID'].' }------'.
                        '<br>Total Climbs: ' . $_SESSION['totalClimbs'] .
                        '<br>Last Session: ' . $_SESSION['lastClimbDate'] .
                        '<br>Hardest Route: ' . $_SESSION['hardestClimb'] .
                '</p>'
                
                .   "<form action=".$path." method='POST'>
                        <fieldset>
                            <legend><b>Search Routes By Grade: </b></legend>
                            <label for='maxGrade'>Max Grade</label>
                            <select name='maxGrade'>
                                <option value='0'>V0</option>
                                <option value='1'>V1</option>
                                <option value='2'>V2</option>
                                <option value='3'>V3</option>
                                <option value='4'>V4</option>
                                <option value='5'>V5</option>
                                <option value='6'>V6</option>
                                <option value='7'>V7</option>
                                <option value='8'>V8</option>
                                <option value='9'>V9</option>
                                <option value='10'>V10</option>
                                <option value='11'>V11</option>
                                <option value='12'>V12</option>
                                <option value='13'>V13</option>
                                <option value='14'>V14</option>
                                <option value='15'>V15</option>
                                <option value='16' selected>V16</option>
                            </select>
                            <label for='minGrade'>Min Grade</label>
                            <select name='minGrade'>
                                <option value='0' selected>V0</option>
                                <option value='1'>V1</option>
                                <option value='2'>V2</option>
                                <option value='3'>V3</option>
                                <option value='4'>V4</option>
                                <option value='5'>V5</option>
                                <option value='6'>V6</option>
                                <option value='7'>V7</option>
                                <option value='8'>V8</option>
                                <option value='9'>V9</option>
                                <option value='10'>V10</option>
                                <option value='11'>V11</option>
                                <option value='12'>V12</option>
                                <option value='13'>V13</option>
                                <option value='14'>V14</option>
                                <option value='15'>V15</option>
                                <option value='16'>V16</option>
                            </select>
                            <input type='submit' class='button' name='Submit' value='Find Route'>
                        </fieldset>
                    </form>"
                
                .   '<form action='.$path.' method="POST">
                    <fieldset>
                        <legend><b>Add a Session</b></legend>
                        <label for="userIDSelect">Select a User: </label>
                        <select name="userIDSelect">'
                    .           makeDropDownOptions("userList", $_SESSION['uID'])
                    .   '</select>
                        <label for="routeIdSelect">Select a Route: </label>
                        <select name="routeIdSelect">' 
                    .           makeDropDownOptions("routeList")
                    .   '</select>
                        <br><label for="sessInfo">Session Information: </label>
                        <input type="textfield" name="sessInfo" placeholder="Enter beta or other important details about the climb">
                        <input type="submit" class="button" name="Submit" value="Add Session">
                    </fieldset>
                    </form>'
                
                .   '<form action='.$path.' method="POST">
                    <fieldset>
                        <legend><b>Add a Route</b></legend>
                        <label for="routeName">Route Name: </label>
                        <input type="text" name="routeName" value="" placeholder="Enter Route Name">
                        <label for="routeIdSelect">Route Difficulty: </label>
                        <select name="routeDiff">
                            <option value="0" selected>V0</option>
                            <option value="1">V1</option>
                            <option value="2">V2</option>
                            <option value="3">V3</option>
                            <option value="4">V4</option>
                            <option value="5">V5</option>
                            <option value="6">V6</option>
                            <option value="7">V7</option>
                            <option value="8">V8</option>
                            <option value="9">V9</option>
                            <option value="10">V10</option>
                            <option value="11">V11</option>
                            <option value="12">V12</option>
                            <option value="13">V13</option>
                            <option value="14">V14</option>
                            <option value="15">V15</option>
                            <option value="16">V16</option>
                        </select>
                        <label for="setterID">Select Setter: </label>
                        <select name="setterID">'
                    .           makeDropDownOptions("userList", $_SESSION['uID'])
                    .   '</select>
                        <br><label for="routeInfo">Route Information: </label>
                        <input type="textfield" 
                            name="routeInfo" 
                            placeholder="Enter beta or other important details about the climb">
                        <input type="submit" class="button" name="Submit" value="Add Route">
                        <br><input type="submit" class="button" name="Submit" value="Change Route Grade">
                        <input type="submit" class="button" name="Submit" value="Change Route Info">
                        <h4>ATTENTION: use route id number in "Route Name" field if you are changing grade or info</h4>
                    </fieldset>
                    </form>'

                    .   "<form action=".$path." method='POST'>
                    <fieldset>
                        <legend><b>Change Wall Angle: </b></legend>
                        <label for='angle'>Angle</label>
                        <select name='wallAngle'>
                            <option value='0'>0</option>
                            <option value='85'>5</option>
                            <option value='10'>10</option>
                            <option value='15'>15</option>
                            <option value='20'>20</option>
                            <option value='25'>25</option>
                            <option value='30'>30</option>
                            <option value='35'>35</option>
                            <option value='40'>40</option>
                            <option value='45'>45</option>
                            <option value='50'>50</option>
                            <option value='55'>55</option>
                            <option value='60'>60</option>
                        </select>
                        <input type='submit' class='button' name='Submit' value='Change Angle'>
                    </fieldset>
                </form>"

                .   "<form action=".$path." method='POST'>
                        <fieldset>
                            <legend><b>Change Password: </b></legend>
                            <label for='oldPass'>Old Password:</label>
                            <input type='password' name='oldPass' placeholder='Enter Current Password'>
                            <label for='newPass'>New Password</label>
                            <input type='password' name='newPass' placeholder='Enter New Password'>
                            <input type='submit' class='button' name='Submit' value='Change Password'>
                        </fieldset>
                    </form>"
                
                .   '<form action='.$path.' method="POST">
                        <input type="submit" class="button" name="Submit" value="View User Sessions">
                    </form>';

        return $userLanding;


        //THESE NEED TO BE IMPLEMENTED TO RESTRICT USER ACCESS


        //SUPER USER HAS ACCESS TO ADMIN AT EVRY WALL
        //ADMIN USERS
        case "adminUserForm":
            //change a users password
            //change a users name
            //delete a user
            //view all users
            break;

        case "adminRouteForm":
            $adminRouteForm = 
                '<form action='.$path.' method="POST">
                    <fieldset>
                        <legend><b>Add a Route</b></legend>
                        <label for="routeName">Route Name: </label>
                        <input type="text" name="routeName" value="" placeholder="Enter Route Name">
                        <label for="routeIdSelect">Route Difficulty: </label>
                        <select name="routeDiff">
                            <option value="0" selected>V0</option>
                            <option value="1">V1</option>
                            <option value="2">V2</option>
                            <option value="3">V3</option>
                            <option value="4">V4</option>
                            <option value="5">V5</option>
                            <option value="6">V6</option>
                            <option value="7">V7</option>
                            <option value="8">V8</option>
                            <option value="9">V9</option>
                            <option value="10">V10</option>
                            <option value="11">V11</option>
                            <option value="12">V12</option>
                            <option value="13">V13</option>
                            <option value="14">V14</option>
                            <option value="15">V15</option>
                            <option value="16">V16</option>
                        </select>
                        <label for="setterID">Select Setter: </label>
                        <select name="setterID">'
                    .           makeDropDownOptions("userList", $_SESSION['uID'])
                    .   '</select>
                        <br><label for="routeInfo">Route Information: </label>
                        <input type="textfield" 
                            name="routeInfo" 
                            placeholder="Enter beta or other important details about the climb">
                        <input type="submit" class="button" name="Submit" value="Add Route">
                        <br><input type="submit" class="button" name="Submit" value="Change Route Grade">
                        <input type="submit" class="button" name="Submit" value="Change Route Info">
                        <h4>ATTENTION: use route id number in "Route Name" field if you are changing grade or info</h4>
                    </fieldset>
                </form>';

            //THIS MAY NEED ADJUSTMENTS
            //change route names
            //change route difficulty
            //change route setter
            //change route set date
            //change route info
            //change route wall id
            //change route img id
            //change route markings
            //change route obsolesence
        return $adminRouteForm;



        case "adminAccessForm": //can verify users for walls they are admin
            //change user priveledges based on wall id
            //dropdown list of walls, or seachbox
                //then when wall selected a table appears with checkboxes to give or revoke privledge
            break;
        
        case "ownerWallForm":
            //if owner
                //delete wall
                //change wall name
                //change wall city
                //change wall state
                //change wall country
            break;

        case "adminSessionForm":
            //view all sessions at a wall
            //delete any session
            break;

        //VERIFIED USERS
        case "verifiedRouteForm": //lets the user add and modify existing routes at a wall that they have permission
            //change route difficulty
            //change route info
            break;

        case "verifiedWallForm": //lets the user see the wall address with location
            //view wall address
            break;

        //GENERAL USERS
        case "routeSearchForm":
            $routeForm = 
                "<form action=".$path." method='POST'>
                    <fieldset>
                        <legend><b>Search Routes By Grade: </b></legend>
                        <label for='maxGrade'>Max Grade</label>
                        <select name='maxGrade'>
                            <option value='0'>V0</option>
                            <option value='1'>V1</option>
                            <option value='2'>V2</option>
                            <option value='3'>V3</option>
                            <option value='4'>V4</option>
                            <option value='5'>V5</option>
                            <option value='6'>V6</option>
                            <option value='7'>V7</option>
                            <option value='8'>V8</option>
                            <option value='9'>V9</option>
                            <option value='10'>V10</option>
                            <option value='11'>V11</option>
                            <option value='12'>V12</option>
                            <option value='13'>V13</option>
                            <option value='14'>V14</option>
                            <option value='15'>V15</option>
                            <option value='16' selected>V16</option>
                        </select>
                        <label for='minGrade'>Min Grade</label>
                        <select name='minGrade'>
                            <option value='0' selected>V0</option>
                            <option value='1'>V1</option>
                            <option value='2'>V2</option>
                            <option value='3'>V3</option>
                            <option value='4'>V4</option>
                            <option value='5'>V5</option>
                            <option value='6'>V6</option>
                            <option value='7'>V7</option>
                            <option value='8'>V8</option>
                            <option value='9'>V9</option>
                            <option value='10'>V10</option>
                            <option value='11'>V11</option>
                            <option value='12'>V12</option>
                            <option value='13'>V13</option>
                            <option value='14'>V14</option>
                            <option value='15'>V15</option>
                            <option value='16'>V16</option>
                        </select>
                        <label for='routeName'>Route Name</label>
                        <input type='text' name='routeName' placeholder='Enter Route Name'>
                        <input type='submit' class='button' name='Submit' value='Find Route'>
                    </fieldset>
                </form>";
        return $routeForm;
        
        case "addSessionForm":
            $sessionForm =
                '<form action='.$path.' method="POST">
                    <fieldset>
                        <legend><b>Add a Session</b></legend>
                        <label for="userIDSelect">Select a User: </label>
                        <select name="userIDSelect">'
                    .           makeDropDownOptions("userList", $_SESSION['uID'])
                    .   '</select>
                        <label for="routeIdSelect">Select a Route: </label>
                        <select name="routeIdSelect">' 
                    .           makeDropDownOptions("routeList")
                    .   '</select>
                        <br><label for="sessInfo">Session Information: </label>
                        <input type="textfield" name="sessInfo" placeholder="Enter beta or other important details about the climb">
                        <input type="submit" class="button" name="Submit" value="Add Session">
                    </fieldset>
                </form>';
        return $sessionForm;

        case "changePasswordForm":
            $passwordForm = 
                    "<form action=".$path." method='POST'>
                        <fieldset>
                            <legend><b>Change Password: </b></legend>
                            <label for='oldPass'>Old Password:</label>
                            <input type='password' name='oldPass' placeholder='Enter Current Password'>
                            <label for='newPass'>New Password</label>
                            <input type='password' name='newPass' placeholder='Enter New Password'>
                            <input type='submit' class='button' name='Submit' value='Change Password'>
                        </fieldset>
                    </form>";
        return $passwordForm;

        case "viewStats":
            $userStats =
                    '<p>'.
                            '------{CURRENT USER: '.$_SESSION['uName']." | ID: ".$_SESSION['uID'].' }------'.
                            '<br>Total Climbs: ' . $_SESSION['totalClimbs'] .
                            '<br>Last Session: ' . $_SESSION['lastClimbDate'] .
                            '<br>Hardest Route: ' . $_SESSION['hardestClimb'] .
                    '</p>';
            //average difficutly
            //most climbs in a session
            //hardest climb
            //total number of climbs
            //number of climbs per month/week
            //average difficulty per month/week
        return $userStats;


    }
}
?> 