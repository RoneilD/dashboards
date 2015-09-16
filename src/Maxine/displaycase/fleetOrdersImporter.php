<?php
//: Preparation
$realPath = realpath(dirname(__FILE__));
$maxine = substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
$rootaccess	= substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
define("BASE", $rootaccess);

include_once(BASE."basefunctions/localdefines.php");
include_once(BASE."basefunctions/dbcontrols.php");
include_once(BASE."basefunctions/baseapis/manapi.php");
include_once(BASE."Maxine/api/maxineapi.php");

require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
//: End
//: Content
$fdh = new fleetDayHandler();
$fdh->importOrders();
//: End