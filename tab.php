<?php
	session_start();
	$id=$_SESSION["id"];
	//logout시
	if($_POST["logout"]=="LOGOUT" || !$id) {
		$message="logout";
		unset($_SESSION["id"]);
		unset($_SESSION["cardno"]);
		header("Location: login.html");
		exit();
	}

	if($id=="admin") {
		//관리자
	}

?>
<html>
	<head>
		<title> Welcome, <? echo $id ?>!</title> 
		<meta charset=UTF-8>
		<link rel="stylesheet" type="text/css" href="style.css"/>
	</head>
	<body>
		<div id="container">
			<div id="header"><?	include "title.php"; ?></div>
			<div id="sidemenu">
				<? 
					if($id=="admin") include "menu_admin.html";
					else include "menu_user.html";
				?>
			</div>
			<div id="content">
				<iframe name="right" width=100% height=100% frameborder=0 marginheight=0 marginwidth=0>
					table
				</iframe>
			</div>
			<div id="footer">
				<div class="rightdiv">made by 강팀
				</div>	
			</div>
		</div>
	</body>
</html>
