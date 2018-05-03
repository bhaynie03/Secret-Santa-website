<?php
return"

	<div class='colOne'>
		
		<form class='wishlistAdd' method='post' action='index.php?page=your-profile'>
			<label>Add Item to Wish List</label>
			<input type='text' name='add-item' $focus />
			<input type='submit' name='submit-wish' value='Submit Wish'  />
		</form>
		<form class='wishlistDelete' method='post' action='index.php?page=your-profile'>
			<label>Your Wish List</label>
			<br>
			$wishListItems
			<label>Oops! Wanna Delete that?</label>
			<input type='submit' name='delete-item' value='Delete Item(s)' />
		</form>
	
		<h3>Your Bio</h3>
		<h3>About You:</h3>
		<p>$bio</p>

		<h3>Hobbies/Likes:</h3>
		<p>$hobbies_Likes</p>

		<h3>Other:</h3>
		<p>$other</p>

		<form class='editbio' method='post' action='index.php?page=your-profile'>
			<label>Tell us more! Update Your Info</label>
			<input type='submit' name='edit-bio' value='Edit Your Bio' />
		</form>
	</div>

</section>

";
//This is the some code that belongs right under $wishListItems to add the dibs capacity which will be used in other pages. Just not this one.
// <input type='submit' name='submit-dibs' value='Submit Dibs' />
				//this is the olde stuff, I'll keep it around for a second
// <h1>$firstName $lastName</h1>
// <h4> Event Selection: $eventName</h4>
// <form method='post' action='index.php?page=your-profile'>
// 	<select name='event-options'>
// 		<option value='0' > Select an event</option>
// 		$eventOptions
// 	</select>
// 	<input type='submit' name'event-selection' value='Go' />
// </form>

// <h4>Buddy Name: $buddyName</h4>
// <h4>Your Wish List: </h4>
// <form method='post' action='index.php?page=your-profile'>
// 	<label>Add Item to Wish List</label>
// 	<input type='text' name='add-item' $focus />
// 	<input type='submit' name='submit-wish' value='Submit Wish'  />
// </form>
// <form method='post' action='index.php?page=your-profile'>
// 	$wishListItems
	
// 	<input type='submit' name='delete-item' value='Delete Item(s)' />
// </form>

// <h3>Your Bio</h3>
// <h4>About You:</h4>
// <p>$bio</p>
// <h4>Hobbies/Likes:</h4>
// <p>$hobbies_Likes</p>
// <h4>Other:</h4>
// <p>$other<p>
// <form method='post' action='index.php?page=your-profile'>
// 	<input type='submit' name='edit-bio' value='Edit Your Bio' />
// </form>