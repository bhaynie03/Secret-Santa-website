<?php
return "<!DOCTYPE html>
<html>
<head>
<title>$pageData->title</title>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<meta http-equiv='Content-Type' content='text/html;charset=utf-8'/>
	<link href='sitecode.css' rel= 'stylesheet' />
	<link href='https://fonts.googleapis.com/css?family=Bad+Script' rel='stylesheet'>
$pageData->css
$pageData->embeddedStyle
</head>
<body>
$pageData->content
$pageData->footer
$pageData->scriptElements
</body>
</html>";
// everything here is returned so that it can be used in index.php with an "include_once" command.
?>

