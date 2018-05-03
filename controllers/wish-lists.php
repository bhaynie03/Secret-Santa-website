<?php

if (!isset($_SESSION['first_name'])){
	$view = include_once "views/restricted.php";
	return $view;
}
include_once "models/Account.class.php";
$accountManager = new Account($db);


$userAccount = $accountManager->getAccount2($_SESSION['account_id']);
$user_id = $userAccount->account_id;
$pageDirection = $_GET['page'];
$pageName = "Wish Lists";
$buddyName = "";
$hostName = "";
$date2 = "";
$time2 = "";
$description = "";

$logoutClicked = isset($_POST['logout']);
if($logoutClicked){
	session_destroy();
	// setcookie("diddly", false, time() - (3600), "/");
	$url = "http://hayniehaven.com/index.php?page=login";
	$statusCode = "303";
	$accountManager->redirect( $url, $statusCode );
}

$eventOptions = $accountManager->getEventOptions($user_id, "options");
if ($eventOptions == ""){
	$eventName = "None Selected";
	$allViewableProfiles = "";
	

}else{
	$eventDefaultID = $accountManager->getEventOptions($user_id, "latest");


	if (isset($_POST['event-options'])){
		if ($_POST['event-options'] == "0"){
			$_SESSION['eventID'] = $eventDefaultID;
		}
		else{
			$_SESSION['eventID'] = $_POST['event-options'];
		}
	}else if (!isset($_SESSION['eventID'])){
		$_SESSION['eventID'] = $eventDefaultID;
	}
	$eventID = $_SESSION['eventID'];
	//event info
	$eventInfo = $accountManager->getEventInfo($eventID);
	$host_id = $eventInfo->host_id;
	$host_account = $accountManager->getAccount2($host_id);
	$hostName = $host_account->full_name;
	$eventName = $eventInfo->event_name;
	$datetime = date_create($eventInfo->event_datetime);
	$date2 = date_format($datetime, 'F jS Y');
	$time2 = date_format($datetime, 'g:ia');
	$description = $eventInfo->description;

	$eventDate = $eventInfo->event_datetime;
	$date = "$eventDate";
	$buddyID = $accountManager->getBuddyID($eventID, $user_id);
	$buddy = $accountManager->getAccount2($buddyID);
	$buddyName = "$buddy->first_name $buddy->last_name";
	$allViewableProfiles = "";
	$allViewableProfiles .= $accountManager->getAllViewableProfiles($user_id, $eventID, $buddyID);
}



// This is where the coding specific to a profile that has been selected.
$stillSelecting = true;
if(isset($_GET['profile'])){
	$stillSelecting = false;
	$profileID = $_GET['profile'];
	$pAccount = $accountManager->getAccount2($profileID);
	$pName = "$pAccount->full_name";
	$pWishListItems = $accountManager->getWishListItems($profileID, $user_id);
	$pBio = $pAccount->bio;
	$pHobbies_Likes = $pAccount->hobbies_likes;
	$pOther = $pAccount->other;
	$suggestionItems = $accountManager->getSuggestionItems($profileID);
	$focus = "";
	



	// this is a feature I decided to take away from the user for themselves since it made it more complicated than simple. I just will have the caveat that you shouldn't buy your own stuff
	if(isset($_POST['submit-dibs']) && isset($_POST['allwishes'])){
		$checkedWishes = $_POST['allwishes'];
		$accountManager->addDibs($checkedWishes, $user_id);
		$pWishListItems = $accountManager->getWishListItems($profileID, $user_id);
	}
	if(isset($_POST['remove-dibs']) && isset($_POST['allwishes'])){
		echo "yo mama";
		$checkedWishes = $_POST['allwishes'];
		$accountManager->removeDibs($checkedWishes, $user_id);
		$pWishListItems = $accountManager->getWishListItems($profileID, $user_id);
	}
	if (isset($_POST['submit-suggestion'])){
		if(empty($_POST['suggestion'])){}
		else{
			$suggestion = $_POST['suggestion'];
			$accountManager->saveSuggestion($suggestion, $profileID, $user_id);
			$suggestionItems = $accountManager->getSuggestionItems($profileID);
			$focus = "autofocus";

		}
	}
	if(isset($_POST['submit-Sdibs']) && isset($_POST['allsuggestions'])){
		$checkedSuggestions = $_POST['allsuggestions'];
		$accountManager->addSDibs($checkedSuggestions, $user_id);
		$suggestionItems = $accountManager->getSuggestionItems($profileID);
	}
	if(isset($_POST['remove-Sdibs']) && isset($_POST['allsuggestions'])){
		$checkedSuggestions = $_POST['allsuggestions'];
		$accountManager->removeSDibs($checkedSuggestions, $user_id);
		$suggestionItems = $accountManager->getSuggestionItems($profileID);
	}
	if(isset($_POST['delete-suggestion']) && isset($_POST['allsuggestions'])){
		$checkedSuggestions = $_POST['allsuggestions'];
		$accountManager->deleteSuggestions($checkedSuggestions, $user_id);
		$suggestionItems = $accountManager->getSuggestionItems($profileID);
	}	
}

if (empty($pWishListItems)){
	$pWishListItems = "<p>It looks like they don't have any items listed yet. Have someone bug them or maybe you can suggest an Item for them bellow</p>";
}
if(empty($hobbies_Likes) && empty($bio) && empty($other)){
	$bio = "<p>It looks like they haven't updated their bio yet. Hopefully they will come up with some content so you will have a better idea what to get them.</p>";
	}








if ($stillSelecting){
	$view = include_once "views/colTwo.php";
	$view .= include_once "views/wish-list-selection-html.php";
}else if($user_id == $profileID){
	$url = "http://hayniehaven.com/index.php?page=your-profile";
	$statusCode = "303";
	$accountManager->redirect( $url, $statusCode );
}else{
	$view = include_once "views/colTwo.php";
	$view .= include_once "views/wish-list-html.php";
}
include_once"views/countdown-js.php";

return $view;
