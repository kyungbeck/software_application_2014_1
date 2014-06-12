<?php
	//$mysqli=mysqli_connect("localhost", "ser","0000","swproject");
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) { //mysql 연결 실패
		$message='<p>DB connect error</p>';	
		exit();
	}
	$message="<p><strong>search result</strong></p>";
	
	//mysql 연결 성공
	$search=$_POST["word"];	
	$check=$_POST["check"];
	$message="<p>".$search."</p>";
	if($check!=1) {
		//처음에는 아무런 결과를 띄우지않는다.	
	}
	else {
		//검색을 한 경우 검색어가 포함된 경우만 출력한다.
			
		$searchsql="SELECT cardno, id, name ,money, cardtype FROM member WHERE cardno LIKE '%".$search."%'";
		$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($searchres);
		if($numres==0) {
			$message.="<p>There is no search result!</p>";
	?>		<script >alert("nosearch"); </script>
	<?	}
		else {
			$message.="<table>";
			$message.="<tr><td>*card No</td><td>*id</td><td>*name</td><td>잔액or월 사용금액</td></tr>";
			while($cardinfo=mysqli_fetch_array($searchres)) {
				$cardno=stripslashes($cardinfo['cardno']);
				$id=stripslashes($cardinfo['id']);
				$name=stripslashes($cardinfo['name']);
				$money=stripslashes($cardinfo['money']);
				$cardtype=stripslashes($cardinfo['cardtype']);
				if($cardtype==1) {
					$ct="잔";
				}
				else if($cardtype==2) {
					$ct="월";
				}
				if($id!="admin" && $cardno!="bus" && $cardno!="city")
					$message.="<tr align='right'><td>".$cardno."</td><td>".$id."</td><td>".$name."</td><td>".$ct.":".$money."</td></tr>";
			}
			$message.="</table>";
			mysqli_free_result($searchres);
			mysqli_close($mysqli);
		}
	}
	
?>
<html>
	<head>
		<title>
			
		</title>
		<link type="text/css" rel="stylesheet" href="style.css">
	</head>
	<body>
		카드 목록 조회<hr>
		<form name="search11" action="View_card.php" method="POST">
			<p>
				<strong>Search Card Number</strong>
				<input type="text" name="word" value="<?php echo $search; ?>"/>
				<input type="hidden" name="check" value=1/>
				<input class="button" type="submit" value="SEARCH!"/>
			</p>
		</form>

		<?php echo $message; ?>
	</body>
</html>
