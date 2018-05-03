<?php
return"

	<div class='colOne'>
		<h3>Viewing $pName</h3>
		<form method='post' action='index.php?page=wish-lists&amp;profile=$profileID'>
		<label>Their Wish List</label>
			<br>
			$pWishListItems
			<input type='submit' name='submit-dibs' value='Submit Dibs' />
			<input type='submit' name='remove-dibs' value='Remove Dibs' />
		</form>
		<h3>Suggested Items</h3>
		<form method='post' action='index.php?page=wish-lists&amp;profile=$profileID'>
			<label>Suggest an Item</label>
			<input type='text' name='suggestion' $focus />
			<input type='submit' name='submit-suggestion' value='Submit Suggestion' /><br>
			$suggestionItems
			<input type='submit' name='submit-Sdibs' value='Submit Dibs' />
			<input type='submit' name='remove-Sdibs' value='Remove Dibs' /><br>
			<input type='submit' name='delete-suggestion' value='Delete your own suggestion' />
		</form>
		<h4>About You:</h4>
		<p>$pBio</p>
		<h4>Hobbies/Likes:</h4>
		<p>$pHobbies_Likes</p>
		<h4>Other:</h4>
		<p>$pOther<p>
	</div>
</section>
";