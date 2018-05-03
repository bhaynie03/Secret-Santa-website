<?php
if (!isset($_COOKIE['userID'])){
	echo "this didn't seem to work";
	
}
else {
	$id = $_COOKIE['userID'];
	echo "id your id #$id?";
}