<?php
  $id=$_POST[id];
  $passwd=$_POST[passwd];
  $repasswd=$_POST[repasswd];
  $name=$_POST[name];
  $title=$_POST[title];
  $cardno=$_POST[cardno];

  //password 확인이 맞지 않는 경우와 card Number가 비어 있는 경우 재입력하도록 한다.  
  if (($passwd != $repasswd) || is_null(cardno) || is_null(id) || is_null(passwd)) {
	header("Location: adduser.html");  
  } 
   
  //이것을 이제 sql에 넣는다.
  $mysqli=mysqli_connect("localhost", "ser", "0000", "swproject"); //sql database 연결
  //localhost에 있는 ser명의 유저가 0000의 비밀번호로 swproject database에 접속
  //$mysqli=mysqli_connect("54.178.195.175", "parkjun", "qqqq", "software_application_2014_1");

  if(mysqli_connect_errno()) { //연결 실패
	header("Location: adduser.html");
	exit();
  }
  else { //연결 성공
	//이미 같은 아이디가 등록된 경우 
	$checksql="SELECT cardno FROM member WHERE id='".$id."' ";
	$ctres=mysqli_query($mysqli, $checksql) or die(mysqli_error($mysqli));
	$count=mysqli_num_rows($ctres);
	if($count>0) {//아이디 중복에 의한 실패
		mysqli_close($mysqli);
		header("Location: adduser.html");
		exit();
	}
	
	//이미 같은 카드번호가 다른 id에 등록된 
	$check="SELECT cardno FROM member WHERE cardno='".$cardno."' and id!=''";
	$rescheck=mysqli_query($mysqli, $check) or die(mysqli_error($mysqli));
	$ccheck=mysqli_num_rows($rescheck);
	if($ccheck>0) {
		mysqli_close($mysqli);
		header("Location: adduser.html");
		exit();
	}

	//카드는 이미 등록되어있고 id를 추가로 등록하는 경우
	$alcheck="SELECT cardno FROM member WHERE cardno='".$cardno."' and id=''";
	$alnum=mysqli_num_rows(mysqli_query($mysqli, $alcheck));
	$sqlupdate="UPDATE member SET id='".$id."', password='".$passwd."', name='".$name."' WHERE cardno='".$cardno."' and id=''";
	$resupdate=mysqli_query($mysqli, $sqlupdate) or die(mysqli_error($mysqli)); //query 실행
	if($alnum==1) { //성공
		//echo "ss";
		mysqli_close($mysqli);
		header("Location: login.html");
		exit();		
	}
	
	//아이디와 카드를 동시에 등록하는 경우
	$sql="INSERT INTO member (cardno, id, password, name) VALUES ('".$cardno."', '".$id."', '".$passwd."', '".$name."')"; //insert query
	$res=mysqli_query($mysqli, $sql); //query 실행
	if($res==true) { //성공
		mysqli_close($mysqli);
		header("Location: login.html");
		exit();
	}
	else { //실패
		mysqli_close($mysqli);
		header("Location: adduser.html");
		exit();
	}
	
  }
 /*
<!--개선 해야 할점 :
   1. member는 사실 돈까지 표시되는데 후불 선불 유무를 추가하고
     선불의 경우 이 카드가 아마도 미리 금액이 등록되있을것으로 보인다.
     1만원 3만원 5만원이라고 본것 같다.
    따라서 미리 카드를 등록해놓고
    id password name을 등록할경우 update하는것이 더 바람직해 보임.
 -->
  아마도 addcard관련 페이지가 생길듯함..
  */
?>

