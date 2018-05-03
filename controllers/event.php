<?php
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== "1"){
	$view = include_once "views/restricted.php";
	return $view;
}
include_once "models/admin.class.php";
$adminManager = new admin($db);
$message = "";
$event_id = $_GET['id'];

$eventContent = $adminManager->getEventPageInfo($event_id);
$eventName = $eventContent->event_name;
$eventID = $eventContent->event_id;
$eventDateTime = $eventContent->event_datetime;
$eventCreated = $eventContent->date_created;

$eventInclusion = $adminManager->getEventPageInclusion($event_id);
$includedContent = "";
while($included = $eventInclusion->fetchObject()){
	$accountID = $included->account_id;
	$userInfo = $adminManager->getAccount($accountID);
	$includedContent .= "<p>$userInfo->first_name $userInfo->last_name and their buddy is $included->buddy_id.</p>";
}


$submitClicked = isset($_POST['submit']);
if ($submitClicked){
	$id = $_POST['buddy-number'];
	$buddy = $adminManager->getAccount($id);
	$firstName = $buddy->first_name;
	$lastName = $buddy->last_name;
	$message .= "$firstName $lastName";
}
$deleteClicked = isset($_POST['delete']);
if ($deleteClicked){
	$adminManager->deleteEvent($event_id);
	$message .= "The event has been deleted.";
}

$view = include_once "views/event-html.php";
return $view;