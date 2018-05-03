<?php 
if (!isset($_SESSION['first_name'])){
	$view = include_once "views/restricted.php";
	return $view;
}
include_once "models/Account.class.php";
$accountManager = new Account($db);
$user_id = $_SESSION['account_id'];
$host_id = $_SESSION["account_id"];
$errorMessage = "";
$errorMessage2 = "";
$buddyResult = "";
$focus = "";
if (isset($_POST['buddy-submit'])){
	$profile = $accountManager->getAccount2($_POST['buddy-finder']);
	$buddyResult = "$profile->full_name";
	$focus = "autofocus";
}
$stagingEvents = $accountManager->getAllHostStagingEvents($host_id);
$liveEvents = $accountManager->getAllHostLiveEvents($host_id);
if ($liveEvents !== "I'm sorry, it look like there aren't any live events you are hosting"){
	$liveEvents .=  "<form method='post' action='http://hayniehaven.com/index.php?page=hosting-event'>
					<label>Buddy Finder</label>
					<input type='number' name='buddy-finder' $focus />
					<input type='submit' name='buddy-submit' value='Submit' />
					</form>
					$buddyResult";
}



if (isset($_POST['go-live'])){
	$event_id = $_POST['event_id'];
	$allIncluded = $_POST['allIncluded'];
	if (empty($allIncluded)){
		$errorMessage2 = "You haven't selected any applicants";
	}
	$stagingEvents = $accountManager->getAllHostStagingEvents($host_id);
	$liveEvents = $accountManager->getAllHostLiveEvents($host_id);
	$errorMessage2 = $accountManager->verifyGoLiveEvent($event_id, $allIncluded);
}

if (isset($_POST['submit'])){
	$eventName = $_POST['event-name'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$description = $_POST['description'];
	$stagingEvents = $accountManager->getAllHostStagingEvents($host_id);
	$liveEvents = $accountManager->getAllHostLiveEvents($host_id);
	$errorMessage = $accountManager->checkStageEvent($eventName, $date, $time, $description, $host_id);
}
if (isset($_POST['delete'])){
	$accountManager->deleteEvent($_POST['event_id']);
	$stagingEvents = $accountManager->getAllHostStagingEvents($host_id);
	$liveEvents = $accountManager->getAllHostLiveEvents($host_id);
}




$logoutClicked = isset($_POST['logout']);
if($logoutClicked){
	session_destroy();
	$url = "http://hayniehaven.com/index.php?page=login";
	$statusCode = "303";
	$accountManager->redirect( $url, $statusCode );
}
$view = include_once "views/hosting-event-html.php";
return $view;