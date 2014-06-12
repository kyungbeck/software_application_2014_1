<?php

	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) {
		$message='<p>DB connect error</p>';
		exit();
	}
	
	mysqli_query($mysqli, 'set names utf8');
	//mysqli_query($mysqli, "SET collation_connection=utf8_unicode_ci");
	//mysqli_query($mysqli, "set session character_set_connection=utf8");
	//mysqli_query($mysqli, "set session character_set_results=utf8");
	//mysqli_query($mysqli, "set session character_set_results=utf8");

	$check=$_POST["check"];
	$time=$_POST["time"];
	
	//$busstop=iconv("utf8", "utf8_unicode_ci", $busstop);
	if($check!=1) {
		//처음
		$message="first";
	}
	else if($time) {
		//echo "1";
		$sql="update time set time = ('".$time."');";
		$insertres=mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
		$message="success";


	} 
	else {
		//오류
		$message="fail";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			input for test
		</title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	</head>
	<body>
		time input<hr>
		<form name="input11" action="time.php" method="POST">
			<table>
				
				<tr>
					<td>
						<strong>time</strong>
						<input type="text" name="time" value="<?php echo $time; ?>"/>
					</td>
				</tr>
				
				
				<tr>
					<td align="right">
						<input type="submit" value="INPUT!"/>
					</td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="check" value=1 />
					</td>
				</tr>

			</table>
		</form>
		<?php echo $message; ?>

	</body>

</html>
