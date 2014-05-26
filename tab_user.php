<?php
	/*session_start();
	$id=$_SESSION["id"];
	if($id) {
		header("Location: login.html");
		exit();
	}*/
?>

<html>
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title> user page </title>
	</head>
	<frameset rows="60, *" noresize>
			<frame src="title.php">
		<frameset cols="200, *" noresize>
			<frame src="menu_user.html" name="left">
			<frame  name="right">
		</frameset>
	</frameset>

</html>

