<?php 
function checkuser(){
if (isset($_SESSION["username"])){
 $username=$_SESSION["username"];
 $password=$_SESSION["password"];
//$password=md5($password); 
$sql="select * from user where username='{$username}' and password='{$password}'";  
$rs=query($sql); 
$rom=mysql_fetch_array($rs);
	if ($password!=$rom['password']){
	header("Location: login.php?err=1");
}
if(isset($_COOKIE['query'])){
	$q=$_COOKIE['query'];
	query($q);
	$nums=mysql_affected_rows();
	if($nums>0){
		setcookie('query','',time()-3600*7*24);
	}
}
}else{
	header("Location:login.php?err=2");
	}
}
function checkinser($inser){
	$username=$_SESSION['username'];
	$arr=array();
	$query= "SELECT * FROM inser WHERE user='{$username}' and inser='{$inser}'";
  $rs=query($query);
  $num=mysql_num_rows($rs);
  if($num){
	  $arr[0]=true;
  }else{
	$arr[0]=false;
  }
  $row=mysql_fetch_array($rs);
  $arr[1]=$row['time'];
 return $arr; 
}
		/*
		Ucon 2.0 user core
		Power by 7gugu
		*/
?>