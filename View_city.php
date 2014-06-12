<?php
	$id= $_SESSION["id"];
	//$mysqli=mysqli_connect("localhost", "ser","0000","swproject");
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) { //mysql 연결 실패
		$message='<p>DB connect error</p>';	
		exit();
	}
	mysqli_query($mysqli, 'set names utf8');
	//$message="<p><strong>search result</strong></p>";
	//////$table="transactional_information";	
	//mysql 연결 성공
	
	$frdt=$_POST["frdt"];
	$todt=$_POST["todt"];
	$busco=$_POST["busco"];
	$frdt2=$frdt."000000";
	$todt2=$todt."235959";
	$check=$_POST["check"]; //check if 처음 else again

	//$message="<p>".$search."</p>";
	if($check!=1) {
		//처음에는 아무런 결과를 띄우지않는다.	
	}
	else {
		//검색을 한 경우 검색어가 포함된 경우만 출력한다.
		$selectsql="SELECT name FROM member WHERE id='".$id."'";
		$selectres=mysqli_query($mysqli, $selectsql) or die(mysqli_error($mysqli));
		$cominfo=mysqli_fetch_array($selectres);
		$Scompany=stripslashes($cominfo['name']); 

		$message="<p><strong>search result</strong></p>";	
		$searchsql="SELECT company, SUM(subsidy) FROM city WHERE company like '%".$busco."%'";
		//특수 검색 시간과 정류장
		if($frdt && $todt) {
			if($frdt2>$todt2) {
				echo $message="time error"; 
				exit();
			}
			//사이
			//echo $message="1";
			$searchsql.=" AND (tagtime BETWEEN '".$frdt2."' AND '".$todt2."')";
		}
		else if($frdt) {
			//이시간이후 탑승기록에 대해 모두 검색
			//echo $message="2";
			$searchsql.=" AND tagtime>='".$frdt2."'";
		}
		else if($todt) {
			//이시간이전 탑승기록에 대해 모두 검색
			//echo $message="3";
			$searchsql.=" AND tagtime<='".$todt2."'";
		}
	
		$searchsql.=" GROUP BY company";
		
		$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($searchres);
		if($numres==0) {
			$message.="<p>There is no search result!</p>";
			//<script >alert("nosearch"); </script>
		}
		else {
			$message.="<table>";
		$message.="<tr><td>*COMPANY</td><td>*보조금액</td></tr>";
			while($useinfo=mysqli_fetch_array($searchres)) {
				$company=stripslashes($useinfo['company']);
				$money=stripslashes($useinfo['SUM(subsidy)']);
				$message.="<tr align=right><td>".$company."</td><td>".$money."</td></tr>";
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
		사업자별 보조금 조회 <hr>
		<form name="search11" action="View_city.php" method="POST">
			<p>
				<table>
					<tr>
						<td>
							<strong>Date From</strong>
							<input type="text" name="frdt" value="<?php echo $frdt; ?>"/>
						</td>
						<td>
							<strong>Date To</strong>
							<input type="text" name="todt" value="<?php echo $todt; ?>"/> 
						</td>
					</tr>

					<tr>	
						<td>
							<strong>BusCompany</strong>
							<input type="text" name="busco" value="<?php echo $busco; ?>"/>
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
