<?php
	//$mysqli=mysqli_connect("localhost", "ser","0000","swproject");
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) { //mysql 연결 실패
		$message='<p>DB connect error</p>';	
		exit();
	}
	//$message="<p><strong>search result</strong></p>";
	$table="transactional_information";	
	//mysql 연결 성공
	$Scardno=$_POST["cardno"];	
	$frdt=$_POST["frdt"];
	$todt=$_POST["todt"];
	$Sbusno=$_POST["busno"];
	$Sbusstop=$_POST["busstop"];
	$check=$_POST["check"]; //check if 처음 else again

	//$message="<p>".$search."</p>";
	if($check!=1) {
		//처음에는 아무런 결과를 띄우지않는다.	
	}
	else {
		//검색을 한 경우 검색어가 포함된 경우만 출력한다.
		$message="<p><strong>search result</strong></p>";	
		$searchsql="SELECT no, cardno, cardtype, persontype, changemoney, ridetagtime, offtagtime, personnumber, ridebusstop, offbusstop, busline, transnumber FROM ".$table." WHERE cardno LIKE '%".$Scardno."%' AND busline LIKE '%".$Sbusno."%'";
		//특수 검색 시간과 정류장
		if($frdt && $todt) {
			if($frdt>$todt) {
				echo $message="time error"; 
				exit();
			}
			//사이
			//echo $message="1";
			$searchsql.=" AND (ridetagtime BETWEEN '".$frdt."' AND '".$todt."' OR offtagtime BETWEEN '".$frdt."' AND '".$todt."')";
		}
		else if($frdt) {
			//이시간이후 탑승기록에 대해 모두 검색
			//echo $message="2";
			$searchsql.=" AND offtagtime>='".$frdt."'";
		}
		else if($todt) {
			//이시간이전 탑승기록에 대해 모두 검색
			//echo $message="3";
			$searchsql.=" AND ridetagtime<='".$todt."'";
		}
		if($Sbusstop) {
			//정류장
			//$Sbusstop=$_POST["busstop"];
			//$message.="<p>".$Sbusstop."</p>";
			$searchsql.=" AND (ridebusstop='".$Sbusstop."' OR offbusstop='".$Sbusstop."')";

		}
		$searchsql.=" ORDER BY ridetagtime";
		
		$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($searchres);
		if($numres==0) {
			$message.="<p>There is no search result!</p>";
			//<script >alert("nosearch"); </script>
		}
		else {
			$message.="<table>";
		$message.="<tr><td>*no</td><td>*card No</td><td>*카드유형</td><td>*사용자</td><td>*사용량</td><td>*탑승시간</td><td>*하차시간</td>
			<td>*탑승자 수</td><td>*탑승정류장</td><td>*하차정류장</td><td>*버스번호</td><td>*환승횟수</td></tr>";
			while($useinfo=mysqli_fetch_array($searchres)) {
				$no=stripslashes($useinfo['no']);
				$cardno=stripslashes($useinfo['cardno']);
				$cardtype=stripslashes($useinfo['cardtype']);
				$persontype=stripslashes($useinfo['persontype']);
				$changemoney=stripslashes($useinfo['changemoney']);
				$ridetagtime=stripslashes($useinfo['ridetagtime']);
				$offtagtime=stripslashes($useinfo['offtagtime']);
				$personnumber=stripslashes($useinfo['personnumber']);
				$ridebusstop=stripslashes($useinfo['ridebusstop']);
				$offbusstop=stripslashes($useinfo['offbusstop']);
				$busline=stripslashes($useinfo['busline']);
				$transnumber=stripslashes($useinfo['transnumber']);
				switch($cardtype) {
					case 1:
						$cardtype="선불카드";
						break;
					case 2:
						$cardtype="후불카드";
						break;
				}
				switch($persontype) {
					case 1:
						$persontype="성인";
						break;
					case 2:
						$persontype="청소년";
						break;
					case 3:
						$persontype="어린이";
						break;
				}
				$message.="<tr align=right><td>".$no."</td><td>".$cardno."</td><td>".$cardtype."</td><td>".$persontype."</td><td>".$changemoney."</td><td>".
					$ridetagtime."</td><td>".$offtagtime."</td><td>".$personnumber."</td><td>".$ridebusstop."</td><td>".$offbusstop."</td><td>".
					$busline."</td><td>".$transnumber."</td></tr>";
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
		승하차 거래 내역 조회 <hr>
		<form name="search11" action="View_use.php" method="POST">
			<p>
				<table>
					<tr>
						<td>
							<strong>Time From</strong> 
							<input type="text" name="frdt" value="<?php echo $frdt; ?>"/>
						</td>
						<td>
							<strong>Time To</strong>
							<input type="text" name="todt" value="<?php echo $todt; ?>"/> 
						</td>
					</tr>

					<tr>	
						<td>
							<strong>Bus Line</strong>
							<input type="text" name="busno" value="<?php echo $Sbusno; ?>"/>
						</td>
						<td>
							<strong>Bus Stop</strong>
							<input type="text" name="busstop" value="<?php echo $Sbusstop; ?>"/>
						</td>
					</tr>
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
