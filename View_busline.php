<?php
	session_start();
	$id=$_SESSION["id"];
	//$mysqli=mysqli_connect("localhost", "ser","0000","swproject");
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) { //mysql 연결 실패
		$message='<p>DB connect error</p>';	
		exit();
	}
	//$message="<p><strong>search result</strong></p>";
	$table="buscompany";	
	//mysql 연결 성공
	$Sbuscompany=$_POST["buscompany"];	
	$Sbusline=$_POST["busline"];
	$check=$_POST["check"]; //check if 처음 else again

	//$message="<p>".$search."</p>";
	if($check!=1) {
		//처음에는 아무런 결과를 띄우지않는다.	
	}
	else {
		//검색을 한 경우 검색어가 포함된 경우만 출력한다.
		$message="<p><strong>search result</strong></p>";	
		$searchsql="SELECT  company, busline FROM ".$table." WHERE company LIKE '%".$Sbuscompany."%' AND busline LIKE '%".$Sbusline."%'";
		$searchsql.=" ORDER BY company, busline";
		
		$searchres=mysqli_query($mysqli, $searchsql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($searchres);
		if($numres==0) {
			$message.="<p>There is no search result!</p>";
			//<script >alert("nosearch"); </script>
		}
		else {
			$message.="<table>";
		$message.="<tr><td>*회사명</td><td>*버스노선번호</td></tr>";
			while($useinfo=mysqli_fetch_array($searchres)) {
				$buscompany=stripslashes($useinfo['company']);
				$busline=stripslashes($useinfo['busline']);
				
				$message.="<tr align=right><td>".$buscompany."</td><td>".$busline."</td></tr>";
			}
			$message.="</table>";
			mysqli_free_result($searchres);
			mysqli_close($mysqli);
		}
	}
	
?>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="style.css">	
		<title>
			
		</title>
	</head>
	<body>
		버스 사업자별 노선 조회 <hr>
		<form name="search11" action="View_busline.php" method="POST">
			<p>
				<table>
					<tr>
						<td>
							<strong>Bus Company</strong>
							<input type="text" name="buscompany" value="<?php echo $Sbuscompany; ?>"/>
						</td>
					</tr>
					<tr> 
						<td>
							<strong>Bus Line</strong>
							<input type="text" name="busline" value="<?php echo $Sbusline; ?>"/>
						</td>
					</tr>
					<tr>
						<td><input type="hidden" name="check" value=1/></td>
						<td align="right">
							<input class="button" type="submit" value="SEARCH!"/>
						</td>
					</tr>
				</table>
			</p>
		</form>

		<?php echo $message; ?>
	</body>
</html>
