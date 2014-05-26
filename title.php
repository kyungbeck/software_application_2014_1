<?php
	session_start();
	$id=$_SESSION["id"];
	$cardno=$_SESSION["cardno"];
	if($id=="admin") {
		echo "<html> <body>	 <table>
				<tr>
					<td><h3>connecting with ".$id." authority</h3> </td>
					<td width='200'></td>
					<td><h5>GANG TEAM</h5></td>
				</tr>
			</table> ";
	}
	else { 
		echo "<html> <body>  <table>
		           <tr>
		           <td><h3>connecting with id: ".$id." and card No.: ".$cardno." </h3> </td>
		         <td width='200'></td>
		                 <td><h5>GANG TEAM</h5></td>
		           </tr>
		         </table> ";
	}
/*	echo "<body>
			<table>
				<tr>
					<td colspan='3'> <h2>USE LIST</h2> </td>
				
				</tr>
			</table>";
*/			

	echo "</body> </html>";
?>
