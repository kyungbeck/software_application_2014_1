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
	$cardno=$_POST["cardno"];
	$cardtype=$_POST["cardtype"];
	$persontype=$_POST["persontype"];
	$changemoney=$_POST["changemoney"];
	$tagtime=$_POST["tagtime"];
	$personnum=$_POST["personnum"];
	$busstop=$_POST["busstop"];
	$busline=$_POST["busline"];
	$transnumber=$_POST["transnumber"];
	//$busstop=iconv("utf8", "utf8_unicode_ci", $busstop);
	if($check!=1) {
		//처음
		$message="first";
	}
	else if($cardno && $cardtype && $persontype && $tagtime && $personnum && $busstop && $busline && $transnumber) {
		//echo "1";
		$sql="insert into table_main (cardno, cardtype, persontype, changemoney, taggingtime, personnumber,busstop,busline,transnumber) values ('".$cardno."', ".$cardtype.", ".$persontype.", ".$changemoney.", '".$tagtime."', ".$personnum.",'".$busstop."','".$busline."', ".$transnumber.")";
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
		input<hr>
		<form name="input11" action="input.php" method="POST">
			<table>
				<tr>
					<td>
						<strong>cardno</strong>
						<input type="text" name="cardno" value="<?php echo $cardno; ?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<strong>cardtype</strong>
						<input type="text" name="cardtype" value="<?php echo $cardtype; ?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<strong>persontype</strong>
						<input type="text" name="persontype" value="<?php echo $persontype; ?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<strong>changemoney</strong>
						<input type="text" name="changemoney" value="<?php echo $changemoney; ?> "/>
					</td>
				</tr>
				<tr>
					<td>
						<strong>tagtime</strong>
						<input type="text" name="tagtime" value="<?php echo $tagtime; ?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<strong>personnum</strong>
						<input type="text" name="personnum" value="<?php echo $personnum; ?> "/>
					</td>
				</tr>
				<tr>
					<td>
						<strong>busstop</strong>
						<input type="text" name="busstop" value="<?php echo $busstop; ?> "/>
					</td>
				</tr>
				<tr>
					<td>
						<strong>busline</strong>
						<input type="text" name="busline" value="<?php echo $busline; ?> "/>
					</td>
				</tr>
				<tr>
					<td>
						<strong>transnumber</strong>
						<input type="text" name="transnumber" value="<?php echo $transnumber; ?>"/>
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
