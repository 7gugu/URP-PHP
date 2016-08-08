<?php 
function checkuser(){
if (isset($_SESSION["username"])){
 $username=$_SESSION["username"];
 $password=$_SESSION["password"];
//$password=md5($password); 
$sql="select * from user where username='{$username}' and password='{$password}'";  
$rs=query($sql); 
$rom=mysqli_fetch_array($rs);
	if ($password!=$rom['password']){
	header("Location: login.php?err=9");
}
if(isset($_COOKIE['query'])){
	$q=$_COOKIE['query'];
	$r=query($q);
	$nums=mysqli_affected_rows($connect);
	if($r){
		setcookie('query','',time()-3600*7*24);
	}
}
}else{
	header("Location:login.php?err=10");
	}
}
function checkinser($inser,$inserpassword){
	global $connect;
	$arr=array();
	$query= "SELECT * FROM inser WHERE password='{$inserpassword}' and inser='{$inser}'";
  $rs=query($query);
  $num=mysqli_num_rows($rs);
  if($num){
	  $arr[0]=true;
  }else{
	$arr[0]=false;
  }
  $row=mysqli_fetch_array($rs);
  $arr[1]=$row['time'];
 return $arr; 
}
		/*
		Ucon 2.0 user core
		Power by 7gugu
		*/
?>