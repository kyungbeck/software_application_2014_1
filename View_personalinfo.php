<?php
	//$mysqli=mysqli_connect("localhost", "ser","0000","swproject");
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) { //mysql 연결 실패
		$message='<p>DB connect error</p>';	
		exit();
	}
	$message="<h2><p><strong>회원 정보</strong></p></h2>";
	
	//mysql 연결 성공
	session_start();
	$id=$_SESSION["id"];	
	$cardno=$_SESSION["cardno"];
	//$message="<p>".$search."</p>";
		
	$searchsql="SELECT cardno, id, name, password FROM member WHERE cardno='".$cardno."'";
	$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
	$numres=mysqli_num_rows($searchres);
	if($numres==0) {
		$message.="<p>There is no search result!</p>";
	}
	else {
		while($cardinfo=mysqli_fetch_array($searchres)) {
			$cardno=stripslashes($cardinfo['cardno']);
			$id=stripslashes($cardinfo['id']);
			$name=stripslashes($cardinfo['name']);
			$password=stripslashes($cardinfo['password']);
			$message.="<strong>*성  명 : ".$name."</strong><br>
				<strong>*카드번호 : ".$cardno."</strong><br>
				<strong>*아이디 : ".$id."</strong><br>
				<strong>*비밀번호 : ".$password;
		}
		mysqli_free_result($searchres);
		mysqli_close($mysqli);
	}
	
	
?>
<html>
	<head>
		<title>
			
		</title>
		<link type="text/css" rel="stylesheet" href="style.css">
	</head>
	<body>
		<!--<form name="search11" action="View_card.php" method="POST">
			<p>
				<strong>Search Card Number</strong>
				<input type="text" name="word" value="<?php echo $search; ?>"/>
				<input type="hidden" name="check" value=1/>
				<input type="submit" value="SEARCH!"/>
			</p>
		</form> -->

		<?php echo $message; ?>
	</body>
</html>
