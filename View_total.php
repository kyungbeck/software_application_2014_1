<?php
	//$mysqli=mysqli_connect("localhost", "ser","0000","swproject");
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) { //mysql 연결 실패
		$message='<p>DB connect error</p>';	
		exit();
	}
	mysqli_query($mysqli, 'set names=utf8');
//$message="<p><strong>search result</strong></p>";
	$table="transactional_information";	
	//mysql 연결 성공

	$Scardno=$_POST["cardno"];
	$check=$_POST["check"]; //check if 처음 else again

	//$message="<p>".$search."</p>";
	if($check!=1) {
		//처음에는 아무런 결과를 띄우지않는다.	
	}
	else {
		//검색을 한 경우 검색어가 포함된 경우만 출력한다.
		$message="<p><strong>search result</strong></p>";	
		$searchsql="select cardno, sum(changemoney) from transactional_information";
		if($Scardno) {
			$searchsql.=" WHERE cardno='".$Scardno."'";
		}
		$searchsql.=" GROUP BY cardno";
		$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($searchres);
		if($numres==0) {
			$message.="<p>There is no search result!</p>";
			//<script >alert("nosearch"); </script>
		}
		else {
			$message.="<table>";
			$message.="<tr><td>*card No</td><td>*누적금액</td></tr>";
			while($useinfo=mysqli_fetch_array($searchres)) {
				$cardno=stripslashes($useinfo['cardno']);
				$summoney = stripslashes($useinfo['sum(changemoney)']);
				$message.="<tr align=right><td>".$cardno."</td><td>".$summoney."</td></tr>";
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
		합산 금액 조회 <hr>
		<form name="search11" action="View_total.php" method="POST">
			<p>
				<table>
					
					<tr>
						<td>
							<strong>Card Number</strong>
							<input type="text" name="cardno" value="<?php echo $Scardno; ?>"/>
						</td>
						<td align="right" >
							<input type="hidden" name="check" value=1/>
							<input class="button" type="submit" value="SEARCH!"/>
						</td>
					</tr>
				</table>
			</p>
		</form>

		<?php echo $message; ?>
	</body>
</html>
