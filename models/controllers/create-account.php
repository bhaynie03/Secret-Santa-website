<?php
$errorMessage = "";
include_once "models/Account.class.php";
$accountManager = new Account($db);
$isNewAccountSubmitted = isset($_POST['input-submitted'] );
if ($isNewAccountSubmitted){
	$errorMessage = $accountManager->checkAccount();
	if ( $errorMessage == "<p>Account saved!</p>" ){
		$fieldData = $accountManager->keepFieldData();
		$fName = $_POST['first-name'];
		$lName = $_POST['last-name'];
		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$accountManager->saveAccount($fName, $lName, $email, $username, $password);	
	}
	$fieldData = $accountManager->keepFieldData();
}


$createAccountForm = include_once "views/create-account-form.php";
return $createAccountForm;























// you need to figure out whether this logic should go into the model of should stay here in the controller. I'm pretty sure this belongs in the model.
//you had a major issue with the submit button, and it turned out that you were missing an "=" in the input type for the submit name. You knew that that problem was that the submit button was not submitting. You didn't know wheather it was a button problem or not. You used a network paramater to help you realize that the submit button wasn't even showing up thanks to deans help.

//what I can do is change the saveAccount() to verifyAccount which will do all the checks it needs to, then if the error message is empty() then I will trigger another function named saveAccount.

//found the problem. It wasn't that I had placed the Return $errorMessage in the wrong place. It's that I used the Return $errorMessage in the first place. Why would I return the $errorMessage? I don't want the index to have it, I want the view to have it!