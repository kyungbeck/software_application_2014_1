<?php
	session_start();
	$id=$_SESSION["id"];
	$cardno=$_SESSION["cardno"];
	$message="";
	if($id=="admin") {
		$message.="
				<tr>
					<td ><h3 id='status'>connecting with ".$id." authority</h3> </td>
					<td width='200'></td>
					<td><h5>GANG TEAM</h5></td>";
	}
	else if($cardno=="bus") {
		$message.="
				<tr>
					<td><h3 id='status'>connecting with ".$id." bus company</h3> </td>
					<td width='200'></td>
					<td><h5>GANG TEAM</h5></td>	";
	}
	else { 
		$message.="<tr>
					<td><h3 id='status'>connecting with id: ".$id." and card No.: ".$cardno." </h3> </td>
		         <td width='200'></td>
		                 <td><h5>GANG TEAM</h5></td>";
	}
/*	echo "<body>
			<table>
				<tr>
					<td colspan='3'> <h2>USE LIST</h2> </td>
				
				</tr>
			</table>";
*/			

	
?>
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	</head>
	<body>
		<table width=1200px height=50px  >
			<?php echo $message; ?>
			<td align="right">
				<form  name="search22" action="tab_admin.php" method="POST">
					<input class="button" name="logout" type="submit" value="LOGOUT" />
				</form>
			</td>
			<td width=10px></td>
			</tr>
		</table>
	</body>
</html>
