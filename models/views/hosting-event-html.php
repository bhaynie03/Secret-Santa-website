<?php
return"

	<div class='colOne'>
		<h3>This is where you host an event!</h3>
		<h3>Current staging Events</h3>
			$stagingEvents
			<p>$errorMessage2</p>
		<h3>Live Events</h3>
			$liveEvents
		<h3>Create a New Event</h3>
		<form method='post' action='index.php?page=hosting-event'>

			<br>
			<label>Event Name</label>
			<input type='text' name='event-name' />
			<br>
			<label>Event Date</label>
			<input type='text' name='date' />
			<label>(yyyy-mm-dd)</label>
			<br>
			<label>Time</label>
			<input type='text' name='time' />
			<label>(hh:mm:ss military time)</label>
			<br>
			<label>Description</label>
			<input type='text' name='description' />
			<br>
			<input type='submit' name='submit' value='Submit' />
			<br>

		</form>
	</div>
</section>

";

					//This is the old stuff that I am going to hold onto for just a little bit
// <h1>This is where you host an event!</h1>


// <h2>Current staging Events</h2>
// $stagingEvents
// <p>$errorMessage2</p>
// <h2>Live Events</h2>
// $liveEvents

// <h2>Create a new Event</h2>
// <form method='post' action='index.php?page=hosting-event'>
// <label>Event Name</label>
// <input type='text' name='event-name' />
// <br>
// <label>Event Date</label>
// <input type='text' name='date' />
// <label>yyyy-mm-dd</label>
// <br>
// <label>Time</label>
// <input type='text' name='time' />
// <label>hh:mm:ss military time</label>
// <br>
// <label>Description</label>
// <input type='text' name='description' />
// <br>
// <input type='submit' name='submit' value='Submit' />
// <br>
// $errorMessage
// </form>