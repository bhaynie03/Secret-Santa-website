<?php
if (!isset($_SESSION['first_name'])){
	$view = include_once "views/restricted.php";
	return $view;
}
include_once "models/Account.class.php";
$accountManager = new Account($db);

$userAccount = $accountManager->getAccount2($_SESSION['account_id']);

$logoutClicked = isset($_POST['logout']);
if($logoutClicked){
	session_destroy();
	// setcookie("diddly", false, time() - (3600), "/");
	$url = "http://hayniehaven.com/index.php?page=login";
	$statusCode = "303";
	$accountManager->redirect( $url, $statusCode );
}

$id = $userAccount->account_id;
$firstName = $userAccount->first_name;
$lastName = $userAccount->last_name;
$bio = $userAccount->bio;
$hobbies_Likes = $userAccount->hobbies_likes;
$other = $userAccount->other;
$focus = "";
$buddyName = "";
$wishListItems = $accountManager->getWishListItems($id, $id);


$eventOptions = $accountManager->getEventOptions($id, "options");
if ($eventOptions == ""){
	$eventName = "None Selected";
}else{
	$eventDefaultID = $accountManager->getEventOptions($id, "latest");


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



	$buddyID = $accountManager->getBuddyID($eventID, $id);
	$buddy = $accountManager->getAccount2($buddyID);
	$buddyName = "$buddy->first_name $buddy->last_name";
}


if (isset($_POST['submit-wish'])){
	if(empty($_POST['add-item'])){}
	else{
		$wish = $_POST['add-item'];
		$accountManager->saveWish($id, $wish);
		$wishListItems = $accountManager->getWishListItems($id, $id);
		$focus = "autofocus";

	}
}
if(isset($_POST['delete-item'])){
	$checkedWishes = $_POST['allwishes'];
	$accountManager->deleteWishes($checkedWishes);
	$wishListItems = $accountManager->getWishListItems($id, $id);
}

// this is a feature I decided to take away from the user for themselves since it made it more complicated than simple. I just will have the caveat that you shouldn't buy your own stuff
// if(isset($_POST['submit-dibs'])){
// 	$checkedWishes = $_POST['allwishes'];
// 	$accountManager->addDibs($checkedWishes, $id);
// 	$wishListItems = $accountManager->getWishListItems($id, $id);
// }



$editing = false;
if (isset($_POST['edit-bio'])){
	$editing = true;
}
if (isset($_POST['bio-submit'])){
	$bio = $_POST['bio'];
	$hobbies_Likes = $_POST['hobbies-likes'];
	$other = $_POST['other'];
	$accountManager->saveBio($id, $bio, $hobbies_Likes, $other);
	$editing = false;
}


if($editing){
	$view = include_once "views/edit-your-bio.php";
}else{
	$view = include_once "views/your-profile-html.php";
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


