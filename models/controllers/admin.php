<?php
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== "1"){
	$view = include_once "views/restricted.php";
	return $view;
}

// if($_SESSION['permission'] !== true){
// 	$view = include_once "views/restricted.php";
// 	return $view;
// }
include_once "models/admin.class.php";
$adminManager = new admin($db);
$errorMessage = "";




$liveEvents = "";
$liveEvents .= $adminManager->getLiveEvents();
$formerEvents = "";
$formerEvents .= $adminManager->getFormerEvents();
$checkboxVariables = "";
$checkboxVariables .= $adminManager->getCheckboxVariables();
$eventSubmitted = isset($_POST['submit']);
if ($eventSubmitted){
	$eventName = $_POST['event-name'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$allIncluded = $_POST['allIncluded'];
	$errorMessage = $adminManager->checkEvent($eventName, $date, $time, $allIncluded);
	$liveEvents = $adminManager->getLiveEvents();
	$formerEvents = $adminManager->getformerEvents();
}
$logoutClicked = isset($_POST['logout']);
if($logoutClicked){
	session_destroy();
	$url = "http://hayniehaven.com/index.php?page=login";
	$statusCode = "303";
	$adminManager->redirect( $url, $statusCode );
}



$view = include_once "views/admin-html.php";
return $view;

// http://localhost/projects/ss2/index.php?page=event&id=24