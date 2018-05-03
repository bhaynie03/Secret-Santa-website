<?php
if (!isset($_SESSION['first_name'])){
	$view = include_once "views/restricted.php";
	return $view;
}
include_once "models/Account.class.php";
$accountManager = new Account($db);

$userAccount = $accountManager->getAccount2($_SESSION['account_id']);
$user_id = $userAccount->account_id;

$logoutClicked = isset($_POST['logout']);
if($logoutClicked){
	session_destroy();
	// setcookie("diddly", false, time() - (3600), "/");
	$url = "http://hayniehaven.com/index.php?page=login";
	$statusCode = "303";
	$accountManager->redirect( $url, $statusCode );
}

$stagingEvents = "";


$stagingEvents = $accountManager->getAllStagingEvents($user_id);
if (empty($stagingEvents)){
	$stagingEvents = "<p>I'm sorry, it looks like there are no staging events right now. Perhaps you would like to host an event yourself and give people the oportunity to join YOUR event?</p>";
}


$logoutClicked = isset($_POST['logout']);
if($logoutClicked){
	session_destroy();
	$url = "http://hayniehaven.com/index.php?page=login";
	$statusCode = "303";
	$accountManager->redirect( $url, $statusCode );
}
if (isset($_GET['id'])){
	$event_id = $_GET['id'];
	$eventInfo = $accountManager->getEventInfo($event_id);
	$host_id = $eventInfo->host_id;
	$host_account = $accountManager->getAccount2($host_id);
	$hostName = $host_account->full_name;
	$eventName = $eventInfo->event_name;
	$datetime = date_create($eventInfo->event_datetime);
	$date = date_format($datetime, 'F jS Y');
	$time = date_format($datetime, 'g:ia');
	$description = $eventInfo->description;
	$button = "";
	$button = $accountManager->getButton($user_id, $event_id, $host_id);
}


if (isset($_POST['go-to-hosting'])){
	$url = "http://hayniehaven.com/index.php?page=hosting-event";
	$statusCode = "303";
	$accountManager->redirect($url, $statusCode );
}
if (isset($_POST['withdraw-application'])){
	if(isset($_POST['event-id'])){
		$event_id = $_POST['event-id'];
	}
	$accountManager->removeApplication($event_id, $user_id);
	if (isset($_GET['id'])){
		$button = $accountManager->getButton($user_id, $event_id, $host_id);
	}else{
	$stagingEvents = $accountManager->getAllStagingEvents($user_id);
	}
}
if (isset($_POST['submit-application'])){
	if(isset($_POST['event-id'])){
		$event_id = $_POST['event-id'];
	}
	$accountManager->submitApplication($event_id, $user_id);
	if (isset($_GET['id'])){
		$button = $accountManager->getButton($user_id, $event_id, $host_id);
	}else{
	$stagingEvents = $accountManager->getAllStagingEvents($user_id);
	}
}

if (isset($_GET['id'])){
	$view = include_once "views/events2-html.php";
}else{
$view = include_once "views/events-html.php";
}


return $view;

// 	<p>dummy event - hosted by Brad Haynie4127<form><input type='submit' value='Apply' name='trail1' /></form></p>
