<?php
return "
<section class='main'>
	
	<h2>Welcome to our Family Christmas Exchange!</h2>
	<p>Here is how it works! </p>
	<p>You will need to sign in and create a password. Write this down! We can create a new account for you but cannot retrieve your password. . . At least not yet. </p>
	<p>Then you need to go to the events page and look for the event name you were told to look for. You can click on the link and see the details or click 'Apply Now.' </p>
	<p>Then you'll want to go to your profile and start adding to your wish list and editing your bio. This is stuff other people will be able to see so you'll want to make sure it has some good content.</p>
	<p>At some point the person hosting the event will 'Go Live' with the event. This will take all the people included and randomly select everyone's buddy, and you'll see more information in the 'Wish Lists' page.</p>

	
	<h1> Secret Santa Login</h1>
	<form class='loginForm' method='post' action='index.php?page=login'>
		<label>Username</label>
		<input class='username' type='text' name='username' />
		<br>
		<label>Password</label>
		<input class='password' type='password' name='password' />
		<input class='submit' type='submit' value='Submit' name='login-submit' />
		<label><a class='create' href='index.php?page=create-account'>Or...Create your Own Account Here!</a></label>
		<br>
	</form>
	
	
</section>

";

















								//This is what I started with
// return "
// <h1> Secret Santa Login</h1>
// <form methor='post' action='index.php'>
// 	<label>username</label>
// 	<input type='text' name='username' />
// 	<br>
// 	<label>password</label>
// 	<input type='password' name='password' />
// </form>
// <h3>Or create your own account</h3>
// <a href='index.php?page=create-account'>Here!</a>
// <br>
// <h6> If you have stumbled upon this website, it is intended for the developer's family, so you can create an account, but you won't be linked to any events going on. Sorry, someday I will make this wildly more available.</h6>

// ";
