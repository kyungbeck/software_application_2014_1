<?php

	if($_POST["logout"]=="LOGOUT") {
		$message="logout";
		unset($_SESSION["id"]);
		unset($_SESSION["cardno"]);
		header("Location: login.html");
		exit();
	}
	session_start();
		
	$id=$_SESSION["id"];
	if($id!="admin") {
		$message="not admin";
		header("Location: login.html");
		exit();
	}
	else {
		$message='
		<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title> admin page </title>
	</head>
	<frameset rows="60, *" noresize>
			<frame src="title.php">
		<frameset cols="200, *" noresize>
			<frame src="menu_admin.html" name="left">
			<frame  name="right">
		</frameset>
	</frameset>';
	}
?>
<html>
	<?php echo $message; ?>
</html>
