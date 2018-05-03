<?php
$fieldDataFound = isset( $fieldData );
if ( $fieldDataFound === false ){
	$fieldData = new StdClass ();
	$fieldData->firstName = "";
	$fieldData->lastName = "";
	$fieldData->email = "";
	$fieldData->username = "";
}
return"
	<a href='index.php?page=login'>Back</a>
<h1>Create your own Account!</h1>
<form method='post' action='index.php?page=create-account'>  
	<label>First-Name</label>
	<input type='text' name='first-name' value='$fieldData->firstName' />
	<br>
	<label>Last-Name</label>
	<input type='text' name='last-name' value='$fieldData->lastName' />
	<br>
	<label>Email</label>
	<input type='text' name='email' value='$fieldData->email' />
	<br>
	<label>Username</label>
	<input type='text' name='username' value='$fieldData->username' />
	<br>
	<label>Password</label>
	<input type='password' name='password' />
	<br>
	<label>Confirm Password</label>
	<input type='password' name='password2' />
	<br>
	<input type='submit' value='Submit' name='input-submitted' />
	$errorMessage
</form>

";



//not entirely sure if my opening <form> tag should have an action of index.php or index.php?page=create-account

// http://localhost/projects/SS2/index.php?page=create-account
// http://localhost/projects/SS2/index.php?create-account