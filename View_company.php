<?php
	//$mysqli=mysqli_connect("localhost", "ser","0000","swproject");
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) { //mysql 연결 실패
		$message='<p>DB connect error</p>';	
		exit();
	}
	$message="";
	
	//mysql 연결 성공
	$search=$_POST["word"];	
	$check=$_POST["check"];
	$frdt=$_POST["frdt"];
	$todt=$_POST["todt"];

	//$message="<p>".$search."</p>";
	if($check!=1) {
		//처음에는 아무런 결과를 띄우지않는다.	
	}
	else {
		//검색을 한 경우 검색어가 포함된 경우만 출력한다.
		$message="<p><strong>search result</strong></p>";			
		$searchsql="SELECT B.company, SUM(changemoney) FROM transactional_information as A, busline_info as B WHERE A.busline = B.busline AND B.company LIKE '%".$search."%'";
		if($frdt && $todt) {
			if($frdt>$todt) {
				echo $message="time error";
				exit();
			}
			$searchsql.=" AND (ridetagtime BETWEEN '".$frdt."' AND '".$todt."' OR offtagtime BETWEEN '".$frdt."' AND '".$todt."')";
		}
		else if($frdt) {
			$searchsql.=" AND offtagtime>='".$frdt."'";
		}
		else if($todt) {
			$searhsql.=" AND ridetagtime<='".$todt."'";
		}

		$searchsql.=" GROUP BY B.company";
		$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($searchres);
		if($numres==0) {
			$message.="<p>There is no search result!</p>";
		}
		else {
			$message.="<table>";
			$message.="<tr><td>*COMPANY</td><td>*수입금</td></tr>";
			while($cardinfo=mysqli_fetch_array($searchres)) {
				$company=stripslashes($cardinfo["company"]);
				$sum=stripslashes($cardinfo['SUM(changemoney)']);
				
				$message.="<tr align=right><td>".$company."</td><td>".$sum."</td></tr>";
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
		버스 사업자별 수입 조회<hr>
		<form name="search11" action="View_company.php" method="POST">
			<p>
				<table>
					<tr>
						<td>
							<strong>Time From</strong>
							<input type="text" name="frdt" value="<?php echo $frdt; ?>" />
						</td>
						<td>
							<strong>Time To</strong>
							<input type="text" name="todt" value="<?php echo $todt; ?> "/>
						</td>
					</tr>
					<tr>
						<td>
							<strong>Busline Number</strong>
							<input type="text" name="word" value="<?php echo $search; ?>"/>
						</td>
						<td align="right">
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
