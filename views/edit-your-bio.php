<?php
return"
<section class='main'>
	<div class='colOne'>
		<a href='index.php?page=your-profile'>Back to profile</a>
		<h1>Edit Your Bio</h1>
		<form method='post' action='index.php?page=your-profile' id='bioform'>
		<h4>About You:</h4>
		<textarea class='biofields' form='bioform' name='bio'>$bio</textarea>
		<h4>Hobbies/Likes:</h4>
		<textarea class='biofields' form='bioform' name='hobbies-likes'>$hobbies_Likes</textarea>
		<h4>Other:</h4>
		<textarea class='biofields' form='bioform' name='other'>$other</textarea>
		<br>
		<input type='submit' name='bio-submit' value='Submit' />
	</div>

</section>





";
// <a href='index.php?page=your-profile>Back</a>