<?php
//so you knew I was cool! admintit!
return"
<form method='post' action='index.php?page=admin'>
	<input type='submit' name='logout' value='logout'>
</form>
<h1>Welcome Administrator</h1>
<h2>Live Events</h2>
$liveEvents
<h2>Former events</h2>
$formerEvents
<h2>Create Event</h2>
<form method='post' action='index.php?page=admin'>
<label>Event Name</label>
<input type='text' name='event-name' />
<br>
<label>Event Date</label>
<input type='text' name='date' />
<label>yyyy-mm-dd</label>
<br>
<label>Time</label>
<input type='text' name='time' />
<label>hh:mm:ss military time</label>
<br>
<label>Who to include:</label>
<br>
$checkboxVariables
<br>
<input type='submit' name='submit' value='Submit' />
$errorMessage
";
// * $checkboxVaraibles = <input type='checkbox' name='allIncluded[]' value='$account->account_id' >$account->first_name $account->last_name<br> several times
