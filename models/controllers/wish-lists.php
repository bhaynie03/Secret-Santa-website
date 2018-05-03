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
	$eventSelected = $accountManager->getEvent($eventID);
	$eventName = $eventSelected->event_name;
	$eventDate = $eventSelected->event_datetime;
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
	$pName = "$pAccount->first_name $pAccount->last_name";
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









if ($stillSelecting){
	$view = include_once "views/wish-list-selection-html.php";
}else if($user_id == $profileID){
	$view = include_once "views/alt-wish-list-html.php";
}else{
	$view = include_once "views/wish-list-html.php";
}


?>
<script>
var countDownDate = new Date("<?php echo $date?>").getTime();
var x = setInterval(function() {
  var now = new Date().getTime();
  var distance = countDownDate - now;

  var months = Math.floor(distance / (1000 *60 * 60 * 24 * 30));
  var days = Math.floor((distance % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 *24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);


  document.getElementById("countdown").innerHTML = months + " months " + days + " days " + hours + ":"
  + minutes + ":" + seconds + "";


  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "EXPIRED";
  }
}, 1000);
</script>


<?php

return $view;
