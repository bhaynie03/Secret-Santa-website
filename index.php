<?php
error_reporting ( E_ALL );
ini_set( "display_errors", 1 );
include_once "models/Page_Data.class.php";
$pageData = new Page_Data ();
$pageData->title = "SS2";
$pageData->addCSS("css/ss2.css");

$dbInfo = "mysql:host=localhost;dbname=ss2";
$dbUser = "bradsguest";
$dbPassword = "Pa55word";
try {
	//try to create a database connection with a PDO object
	$db = new PDO( $dbInfo, $dbUser, $dbPassword );
	$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	//pretty sure this allows me to see errors if there are any
	$pageData->content = "<h1>We're connected</h1>";
	$pageData->footer = include_once "views/footer.php";
}
catch (Exception $e ) {
	$pageData->content = "<h1>Connection failed!</h1><p>$e</p>";
}
//$pageData->content = include_once "views/navigation.php";
$navigationClicked = isset($_GET['page']);
if ($navigationClicked) {
	$filetoLoad = $_GET['page'];
}
else {
	$filetoLoad = "login";
}
$pageData->content = "";
if ( isset($_GET['page']) && ($filetoLoad == "your-profile" || $filetoLoad == "wish-lists" || $filetoLoad == "events" || $filetoLoad == "hosting-event")){
	session_start();
	include_once "models/Account.class.php";
	if ( isset($_SESSION["first_name"])){
		$fullName = $_SESSION["fullName"];
		$pageData->content .= include_once "views/header.php";
		$pageData->content .= include_once "views/navigation.php";

	}
}else{
		session_start();
		$fullName = "";
		$pageData->content .= include_once "views/header.php";
		$pageData->content .= include_once "views/navigation.php";
}
// if(isset($_GET['page']) && ($filetoLoad !== "login" && $filetoLoad !== "create-account") ){
// 	// $filetoLoad == "your-profile"
// 	if ($filetoLoad == "admin"){
// 		session_start();
// 		$_SESSION["permission"] = true;
// 	}else{
// 		session_start();
// 		include_once "models/account.class.php";
// 		$pageData->content .= include_once"views/navigation.php";
// 	}
// }
	$pageData->content .= include_once "controllers/$filetoLoad.php";
	$page = include_once "views/page.php";
	echo $page;








	// I need to make a way for every individual to host an event



 

	// make a page for 'hosting' an event a lot like an administrator page.

	// make a page for 'requesting' to join an event.