<?php
include_once "models/Account.class.php";
$accountManager = new Account($db);


session_destroy();
	// setcookie("diddly", false, time() - (3600), "/");
	$url = "http://hayniehaven.com/index.php?page=login";
	$statusCode = "303";
	$accountManager->redirect( $url, $statusCode );