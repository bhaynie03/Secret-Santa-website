<?php
include_once "models/Account.class.php";
$accountManager = new Account($db);
$loginSubmitted = isset($_POST['login-submit'] );
if ($loginSubmitted){
	$uName = $_POST['username'];
	$pWord = $_POST['password'];
	$errorMessage = $accountManager->checkLogin($uName, $pWord);
	if( empty($errorMessage) ){
						//add whatever you need to.
		$accountInfo = $accountManager->getAccount($uName);
		$id = $accountInfo->account_id;
		$url = "http://hayniehaven.com/index.php?page=your-profile";
		$statusCode = "303";
		// setcookie("userID", "$id", time() + (3600), "/"); 
		// echo $_COOKIE['userID'];
		session_start();
		$_SESSION["user_name"] = $accountInfo->user_name;
		$_SESSION["account_id"] = $id;
		$_SESSION['first_name'] = $accountInfo->first_name;
		$_SESSION["last_name"] = $accountInfo->last_name;
		$_SESSION["email"] = $accountInfo->email;
		$_SESSION["admin"] = $accountInfo->admin;
		$_SESSION["fullName"] = $accountInfo->full_name;
		// setcookie("diddly", true, time() + (3600), "/");
		if(isset($_SESSION['first_name'])){
			if($_SESSION["admin"] == 1){
				$url = "http://hayniehaven.com/index.php?page=admin";
				$accountManager->redirect( $url, $statusCode );
			}else{
			$accountManager->redirect( $url, $statusCode );
		}
		}else{
			echo "session is not set";
		}
	}
	else {
		$loginView = include_once "views/login-html.php";
		return $loginView;
	}
}


else{
$errorMessage = "";
$loginView = include_once "views/login-html.php";
return $loginView;
}









					//This is what I started with, and I may want to go back to this before I start hashing the passwords.
// $loginView = include_once "views/login-html.php";
// return $loginView;

