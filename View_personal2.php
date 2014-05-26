<?php
	//$mysqli=mysqli_connect("localhost", "ser","0000","swproject");
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) { //mysql 연결 실패
		$message='<p>DB connect error</p>';	
		exit();
	}
	
	$message="<h2><p><strong>회원 정보</strong></p></h2>";
	//mysql 연결 성공
	$check=$_POST["check"];
	//$message="<p>".$search."</p>";
	if($check!=1) {
		//처음에는 아무런 결과를 띄우지않는다.	
		session_start();
		$id=$_SESSION["id"];
		$searchsql="SELECT cardno, id, name, password FROM member WHERE id='".$id."'";
		$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($searchres);
		//if($numres==1) {
			$userinfo=mysqli_fetch_array($searchres);//) {
				
			$cardno=$_SESSION["cardno"];
			$cardno=stripslashes($userinfo["cardno"]);
				//$id=stripslashes($userinfo["id"]);
				$name=stripslashes($userinfo["name"]);
				$password=stripslashes($userinfo["password"]);
			//}
		//}
		mysqli_free_result($searchres);
		mysqli_close($mysqli);
	}
	else {
		//검색을 한 경우 검색어가 포함된 경우만 출력한다.
		session_start();
		$id=$_SESSION["id"];
		$name=$_POST["myname"];
		$cardno=$_POST["cardno"];
		$password=$_POST["password"];

		//$updatesql="UPDATE member SET password='".$password."', cardno='".$cardno."' WHERE id='".$id."'";
		//$updateres=mysqli_query($mysqli, $updatesql) or die(mysqli_error($mysqli));
		$searchsql="SELECT cardno, id FROM member WHERE cardno='".$cardno."'";
		$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($searchres);
		$cardinfo=mysqli_fetch_array($searchres);
		if($numres==0 || $cardinfo["id"]=="") {//같은카드번호가 없는경우, id가 배정받기 전인 경우
			if($cardinfo["id"]=="") {
				$deletesql="DELETE FROM member WHERE cardno='".$cardno."'";
				$deleteres=mysqli_query($mysqli,$deletesql) or die (mysqli_error($mysqli));
			}
			$updatesql="UPDATE member SET password='".$password."', cardno='".$cardno."' WHERE id='".$id."'";
			$updateres=mysqli_query($mysqli, $updatesql) or die(mysqli_error($mysqli));
			mysqli_close($mysqli);
			$_SESSION["cardno"]=$cardno;
			$message2="변경 성공";
		}
		else{//같은 카드번호가 있는경우
			$cardno=$_SESSION["cardno"];
			$updatesql="UPDATE member SET password='".$password."' WHERE id='".$id."'";
			$updateres=mysqli_query($mysqli, $updatesql) or die(mysqli_error($mysqli));
			mysqli_close($mysqli);
			$message2="카드 번호 변경 실패, 비밀 번호 변경 성공";
		}
	}
	
?>
<html>
	<head>
		<title>
			
		</title>
	</head>
	<body>
		<?php echo $message; ?>
		<form name="search11" action="View_personal2.php" method="POST">
			<p>
				<strong>*성 명 : <?php echo $name; ?> </strong><br>
				<strong>*카드번호 : 
					<input type="text" name="cardno" value="<?php echo $cardno; ?>"/></strong><br>
				<strong>*아이디 : <?php echo $id; ?> </strong><br>
				<strong>*비밀번호 : 
					<input type="text" name="password" value="<?php echo $password; ?>"/></strong><br>
				<br><br>
				<h5><?php echo $message2; ?></h5>	
				<input type="hidden" name="myname" value="<?php echo $name; ?>"/>
				<input type="hidden" name="check" value=1 />
				<input type="submit" value="변경사항 저장"/>
			</p>
		</form>

		
	</body>
</html>
