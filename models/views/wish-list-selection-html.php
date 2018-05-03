<?php
return"
<div class= 'colOne'>


			<h3>Event Selected: $eventName</h3>
			<h3>Host: $hostName </h3>
			<h3>Date: $date2</h3>
			<h3>Time: $time2</h3>
			<h3>Description:</h3>
			<p>$description</p>
			<h3>Group List for event:</h3>
			<ul>
				$allViewableProfiles
			</ul>
		</div>
	</section>

";

					//This is the old stuff that I will hold onto for a little bit
// <h1> The wish list page</h1>
// <h3>Event Selected: $eventName</h3>
// <h3>Host: $hostName</h3>
// <h3>Date: $date2</h3>
// <h3>Time: $time2</h3>
// <h3>Description:</h3>
// <p>$description</p>
// <form method='post' action='index.php?page=wish-lists'>
// 	<select name='event-options'>
// 		<option value='0' > Select an event</option>
// 		$eventOptions
// 	</select>
// 	<input type='submit' name'event-selection' value='Go' />
// </form>

// <ul>
// 	$allViewableProfiles
// </ul>