<?php
return "
	<div class='colOne'>
		<h3>All Staging events</h3>
			<form method='post' action='index.php?page=events'>
				$stagingEvents
			</form>

		<h3><a href='index.php?page=hosting-event'> Or. . . Host Your Own Event!</a><br><br></h3>
	</div>
</section>

";

//the "or Host Your Own Event!" shows up white at the bottom. Might be fixable