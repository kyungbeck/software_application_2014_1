<?php
  $id=$_POST[id];
  $passwd=$_POST[passwd];
  $repasswd=$_POST[repasswd];
  $name=$_POST[name];
  $title=$_POST[title];
  $cardno=$_POST[cardno];

  //password 확인이 맞지 않는 경우와 card Number가 비어 있는 경우 재입력하도록 한다.  
  if (($passwd != $repasswd) || is_null(cardno)) {
	header("Location: adduser.html");  
  } 
   
  //이것을 이제 sql에 넣는다.
  $mysqli=mysqli_connect("localhost", "ser", "0000", "swproject"); //sql database 연결
  //localhost에 있는 ser명의 유저가 0000의 비밀번호로 swproject database에 접속

  if(mysqli_connect_errno()) { //연결 실패
	exit(); 	 
	header("Location: adduser.html");
	
  }
  else { //연결 성공
	/*개선 해야 할점 : 
	  1. member는 사실 돈까지 표시되는데 후불 선불 유무를 추가하고
	  선불의 경우 이 카드가 아마도 미리 금액이 등록되있을것으로 보인다.
	  1만원 3만원 5만원이라고 본것 같다.
	  따라서 미리 카드를 등록해놓고 
	  id password name을 등록할경우 update하는것이 더 바람직해 보임.
	  2. 사용자 중복에 대한 체크를 생략한 상태
	 */  
	$sql="INSERT INTO member (cardno, id, password, name) VALUES ('".$cardno."', '".$id."', '".$passwd."', '".$name."')"; //insert query
	$res=mysqli_query($mysqli, $sql); //query 실행
	if($res==true) { //성공
		mysqli_close($mysqli);
		header("Location: login.html");
	}
	else { //실패
		mysqli_close($mysqli);
		header("Location: adduser.html");
	}
    

  }



?> 


