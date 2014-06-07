<?php
  $id=$_POST[id];
  $passwd=$_POST[passwd];
  if($_POST["logout"]) {
	unset($_SESSION["id"]);
	unset($_SESSION["cardno"]);
	header("Location: login.html");
	exit();
  }

  //$mysqli=mysqli_connect("localhost", "ser", "0000", "swproject");
  $mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");
  if(mysqli_connect_errno()) {
	  exit();
	  header("Location: login.html");
  }
    
  $check_sql="SELECT cardno, id FROM member WHERE id='".$id."' and password='".$passwd."'";

  $res=mysqli_query($mysqli, $check_sql) or die(mysqli_error($mysqli));
  $count=mysqli_num_rows($res);//결과 수 세기

  if($count==1) {//login 성공
	//echo "log-in by $id<br>";
    //$buscheck==strstr($id,"bus");
	$userinfo=mysqli_fetch_array($res);
	
	session_start();
	$_SESSION["id"]=$id;
	$_SESSION["cardno"]=stripslashes($userinfo['cardno']);
	//header("Location: allView.html");
	mysqli_close($mysqli);
	header("Location: tab.php");
	//관리자 계정
	//if($id=="admin") {
	  //$_SESSION["id"]=$id;
	  /*$to="admin";
	  echo("
			  <body onload= 'window.document.admin.submit()'>
			  <form name= 'admin' action='tab_admin.php' method='post'>
			  <input name=to type=hidden value='");
	  echo($to);
	  echo("'> </form></body>");*/
	  //$_SESSION["id"]=$id;
	  //header("Location: tab_admin.php");
	  exit();
	//}
	//bus사업자 계정
	//if($buscheck!==false) {
	  /*echo("
			  <body onload= 'window.document.bus.submit() '>
			  <form name= 'bus' action='View_bus.php' method='post'>
			  <input name=id type=hidden value='");
	  echo($id);
	  echo("'> </form></body>");*/
	  //header("Location: tab_company.php");
	  //exit();
	//}
	//일반 사용자 계정
	  /*echo("
			  <body onload='window.document.id.submit() '>
			  <form name='id' action='View_id.php' method='post'>
			  <input name=id type=hidden value='");
	  echo($id);
	  echo("'> </form></body>");*/
	  //header("Location: tab_user.php");
	  //exit();
	//}
	
  }
  else { //로그인 실패
    echo "fail";
	mysqli_close($mysqli);
	header("Location: login.html");
  }
?>
