<?php

ob_start();

//: Preparation
$times = substr_count($_SERVER['PHP_SELF'],"/");
$rootaccess = "";
$i = 1;

while ($i < $times) {
    $rootaccess .= "";
    //$rootaccess .= "../";
    $i++;
}

$rootaccess = "../";

//: Defines
define("BASE", $rootaccess);
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
defined("SALT") || define("SALT", "bWLMq4c");
//: End
include_once(BASE."/basefunctions/baseapis/sessions.php");
$session = new Baseapis_Sessions();
session_start();

if (!isset($_SESSION["userid"]))
{
        $_SESSION["userid"] = 0;
}

//: Includes
include_once(BASE."/basefunctions/localdefines.php");
include_once(BASE."/basefunctions/baseapis/manapi.php");
include_once(BASE."/basefunctions/dbcontrols.php");
include_once(BASE."/basefunctions/fpdf.php");
include_once(BASE."/basefunctions/maxgraphs/maxgraphs.php");
include_once(BASE."/basefunctions/maxgraphs/maxgraphdrawer.php");
include_once(BASE."/basefunctions/baseapis/fleetDayHandler.php");
include_once(BASE."basefunctions/baseapis/cache/mechanisms/CacheApc.php");
include_once(FIRSTBASE."/menupages.php");
include_once(FIRSTBASE."/canvass.php");
include_once(FIRSTBASE."/api/maxineapi.php");
include_once(FIRSTBASE."/rightscontrol.php");
include_once(FIRSTBASE."/displaycase/displaycase.php");
include_once(FIRSTBASE."/displaycase/pullFleetDays.php");
include_once(FIRSTBASE."/personneldir/usercontrols.php");
include_once(FIRSTBASE."/api/Users.class.php");
//: End

//: End
//: Content
function home()
{
	if (isset($_SESSION) && array_key_exists("userid", $_SESSION) && ($_SESSION["userid"] > 0))
	{
		header("Location: /");
		exit;
	}
	
	//HTML
	include("./displaycase/content/login.php");
		
}

function login()
{
	print("<pre>");
	print_r($_POST);
	print(PHP_EOL);
	# print_r(get_defined_functions());
	print_r(PHP_EOL);
	$usermatch = sqlPull(array("table"=>"users", "where"=>"`username`='".$_POST["username"]."'", "onerow"=>"1"));
	$password = (string)substr(md5(SALT.$_POST["pass_word"]),0 , 30);
	print($password.PHP_EOL);
	print("usermatch:");
	print_r($usermatch);
	print("</pre>");
	if (isset($usermatch) && is_array($usermatch) && array_key_exists("username", $usermatch))
	{
		if (array_key_exists("password", $usermatch) && ($password === $usermatch["password"]))
		{
			//: Login was successful -- woohoo
			$_SESSION["userid"] = $usermatch["personid"];
			if(array_key_exists("isit", $usermatch) && ($usermatch["isit"] == 1)) {
                                $_SESSION["isit"] = 1;
                        }
			if(array_key_exists("isadmin", $usermatch) && ($usermatch["isadmin"] == 1)) {
				$_SESSION["isadmin"] = 1;
			}
			header("Location: /?personal");
		}
		else
		{
			header("Location: /?loginfailed");
		}
	}
	else
	{
		header("Location: /?loginfailed");
	}
}

function logout()
{
	session_destroy();
	header("Location: /");
}
//: End
//: Switch
$keys = array_keys($_GET);
// print_r($keys);
$action = (isset($keys[1]) ? $keys[1] : (isset($keys[0]) ? $keys[0] : ""));
// print($action);
if (isset($_SESSION) && array_key_exists("userid", $_SESSION) && ($_SESSION["userid"] > 0))
{
	switch ($action)
	{
		case "blackout":        	displayBlackoutDash();  	break;
		case "checkfleetscoreupdates":	checkFleetScoreUpdates();	break;
		case "contribution":		displayContribDash();		break;
		case "exportfleetscoreupdates":	exportFleetScoreUpdates();	break;
		case "fetchrightdays":		fetchRightDays();		break;
        case "fleetDayImport":  	runFleetDayImport();    	break;
		case "fleetpositions":		displayFleetPositionsDash();	break;
        case "greenmile":       	displayGreenmileDash(); 	break;
		case "logout":			logout();			break;
        case "main":            	displayMainDash();      	break;
		case "mydashdetails":		myDashDetails();		break;
        case "offline":         	displayNoDash();        	break;
		case "personal":		displayMyDash();		break;
		case "runfleetimport":		runFleetDayImport();		break;
		case "updatemydashdetails":	updateMyDashDetails();		break;
		//: Users
		case "listusers":	 	listUsers();			break;
		case "edituser":		editUserForm();			break;
		case "commituser":		commitUser();			break;
		case "deleteuser":		deleteUser();			break;
		case "savecustomslide":	saveCustomSlide();		break;
		case 'getusersliders': getUserSliders(); break;
		//: End
        default:                	home();                 	break;
	}
}
else
{
	switch ($action)
	{
		case "blackout":	displayBlackoutDash();	break;
		case "fleetDayImport":	runFleetDayImport();	break;
		case "greenmile":	displayGreenmileDash();	break;
		case "login":		login();		break;
		case "main":		displayMainDash();	break;
		case "offline":		displayNoDash();	break;
		default:		home();			break;
	}
}
//: End
ob_end_flush();