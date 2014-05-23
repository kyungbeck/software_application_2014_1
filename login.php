<?php
  $id=$_POST[id];
  $passwd=$_POST[passwd];

  //$mysqli=mysqli_connect("localhost", "ser", "0000", "swproject");
  $mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
  if(mysqli_connect_errno()) {
	  exit();
	  header("Location: login.html");
  }
  
  $check_sql="SELECT id FROM member WHERE id='".$id."' and password='".$passwd."'";

  $res=mysqli_query($mysqli, $check_sql) or die(mysqli_error($mysqli));
  $count=mysqli_num_rows($res);//결과 수 세기

  if($count==1) {//login 성공
	echo "log-in by $id<br>";
    


	mysqli_close($mysqli);
  }
  else { //로그인 실패
    echo "fail";
	mysqli_close($mysqli);
	header("Location: login.html");
  }
?>
