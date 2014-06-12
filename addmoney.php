<?php
	$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
	if(mysqli_connect_errno()) {
		$message="<p>DB connect error</p>";
		exit();
	}
	mysqli_query($mysqli, 'set names utf8');

	$check= $_POST["check"];
	$cardno=$_POST["cardno"];
	$money=$_POST["money"];

	if($check!=1) {
		$message="first";
	}
	else if($cardno && $money) {
		$sql="SELECT money from member WHERE cardno='".$cardno."'";
		$selectres=mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
		$numres=mysqli_num_rows($selectres);
		if($numres==0) {
			$message="fail";
		}
		else {
			$select=mysqli_fetch_array($selectres);
			$Smoney=stripslashes($select['money']);
			$money=$Smoney+$money;
			$replacesql="UPDATE member SET money=".$money." WHERE cardno='".$cardno."'";
			$replace=mysqli_query($mysqli, $replacesql) or die(mysqli_error($mysqli));
			$message="success";

		}
		//mysqli_close();
	}
	else {
		$message="fail";
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			add money for 선불카드
		</title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	</head>
	<body>
		add money<hr>
		<form name="addd" action="addmoney.php" method="POST">
			<table>
				<tr>
					<td>
						<strong>cardno</strong>
						<input type="text" name="cardno" value="<?php echo $cardno; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<strong>충전금액</strong>
						<input type="text" name="money" value="<?php echo "현재금액: ".$money; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
						<input type="submit" value="충전!" />
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

