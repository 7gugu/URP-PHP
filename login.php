﻿<?php 
require 'function/corestart.php';
if(isset($_POST{'username'})&&isset($_POST['password'])){
		if($_POST['username']==""||$_POST['password']==""){
			echo "<script>alert(\"用户名或密码都不能为空\");</script>";
			sleep(2);
			echo "<script>location.href='login.php';</script>";  
		}
$username=$_POST["username"];
$password=$_POST["password"];
//$password=md5($password); 
$sql="select * from user where username='{$username}' and password='{$password}'";  
$rs=query($sql); 
$rom=mysql_fetch_array($rs);
		if($password===$rom['password']){
    echo "<script>alert('Login success!')</script>";
$_SESSION['username']=$username;
$_SESSION['password']=$password;
$_SESSION['sec']=$rom['admin'];
setcookie("wel", '1', time()+3600);
sleep(3);
header("location:index.php");
    }else{
echo "<script>alert('Login failed!')</script>";
sleep(3);
header("Location: login.php");
} 
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>UCON | login</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
        content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <script src="assets/js/jquery.min.js"></script>
  <link rel="alternate icon" type="image/png" href="assets/i/favicon.png">
  <link rel="stylesheet" href="assets/css/amazeui.min.css?v=1"/>
  <link rel="stylesheet" href="assets/css/nprogress.css"> 
        <link rel="stylesheet" href="assets/css/main.css"> 
 <style>
    .header {
      text-align: center;
    }
    .header h1 {
      font-size: 200%;
      color: #333;
      margin-top: 30px;
    }
    .header p {
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="am-g">

  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
  <div class="am-vertical-align" style="height: 150px;">
  <div class="am-vertical-align-middle">
    <h3>登录</h3>
    
	</div></div>
	<hr>
    <br>
    <br>
	<?php 
	if(isset($_session['username'])&&isset($_session['password'])){
		$username=$_session["username"];
        $password=$_session["password"];
$sql="select * from user where username='{$username}' and password='{$password}'";  
$rs=mysql_query($sql); 
$rom=mysql_fetch_array($rs);
		if($password===$rom['password']){
	echo "
	<ul class=\"am-list confirm-list\" id=\"doc-modal-list\">
	<p>我们侦测到你已登录以下账号,使用它们登陆么?</p>
	<li><a href=\"index.php\">{$username}</a></li>
	</ul>
	";
	}
	}
	?>
    <form method="post" class="am-form">
      <label for="email">用户名:</label>
      <input type="text" name="username" id="username" value="">
      <br>
      <label for="password">密码:</label>
      <input type="password" name="password" id="password" value="">
      <br>
      <br />
      <div class="am-cf">
        <input type="submit" name="" value="登 录" class="am-btn am-btn-primary am-btn-sm am-fl">
       
      </div>
    </form>
    <hr>
    <p>© Power By 7gugu.</p>
  </div>
</div>
</body>

</html>
