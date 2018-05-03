<?php
return"
<a href='http://hayniehaven.com/index.php?page=admin'>Back</a>
<h1>$eventName</h1>
<h4>Event ID: $eventID.</h4>
<h4>Event Date and Time: $eventDateTime.</h4>
<h4>Date created: $eventCreated.</h4
<h3>Included Members:</h3>
	<ul>
	$includedContent
	</ul>
<form method='post' action='http://hayniehaven.com/index.php?page=event&id=$event_id'>
	<label>Buddy Number</label>
	<input type='number' name='buddy-number' />
	<br>
	<input type='submit' name='submit' value='Submit' />
	<input type='submit' name='delete' value='Delete event' />
</form>
<p>$message</p> 
";
