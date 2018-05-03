<?php
return"
<section class='main'>

	<div class='colTwo'>
		<h1 class='pageName'>$pageName</h1>
		<p class='identify'><strong>$fullName</strong></p>
		<p class='countdown'></p>
		<h4> Event Selection: $eventName</h4>
		<h4>Buddy Name: $buddyName</h4>
		<form class='event' method='post' action='index.php?page=$pageDirection'>
			<label>Choose an Event</label>
			<select name='event-options'>
			<option value='0' > Select an event</option>
			$eventOptions
			</select>
			<input type='submit' name'event-selection' value='Go' />
		</form>

	</div>

";
//need to find out if the $fullName variable transfers from the index to any given page.