<?php 
require 'function/corestart.php';
if(isset($_GET['reg'])){
	if($_POST['username']==""||$_POST['password']==""||$_POST['cpassword']==""||$_POST['email']){
			echo "<script>alert(\"用户名或密码或邮箱都不能为空\");</script>";
			sleep(2);
			echo "<script>location.href='login.php?reg&err=16';</script>";  
		}
		if($_POST['password']！=$_POST['cpassword']){
			echo "<script>alert(\"重复密码有误\");</script>";
			sleep(2);
			echo "<script>location.href='login.php?reg&err=17';</script>";  
		}
	$user=$_POST['username'];
	$paw=$_POST['password'];
	$cpw=$_POST['cpassword'];
	$email=$_POST['email'];
		$uid = query("select * from user order by id DESC limit 1 ");
	$uid=mysqli_fetch_array($uid);
	if($uid){
		$uid=$uid['id'];
	}else{
	$uid=0;
	}
	$uid++;
	query("insert into user(id,username,password,admin,email)values('$uid','$user','$paw','0','$email')");
	$numb=mysqli_affected_rows($connect);
	if($numb<=0){
		header("Location: login.php?reg&err=15");
	}else{
		header("Location: login.php?suc=10");
	}
	exit();
}
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
$rom=mysqli_fetch_array($rs);
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
	<?php if(isset($_GET['err'])){?>	
	<div class="am-alert am-alert-warning" data-am-alert>
  <button type="button" class="am-close">&times;</button>
  <p>
  <?php
  switch($_GET['err']){
    case 15:
        echo "[1015]注册失败";
        break;
    case 16:
        echo "[1016]信息缺失,无法注册";
        break;
		case 17:
        echo "[1017]重复密码不相同";
        break;
    default:
        echo "[XXXX]出现了一个未知错误,请尽快联系管理员解决";
  }?>
  </p>
</div>
<?php }?>
<?php if(isset($_GET['suc'])){?>	
	<div class="am-alert am-alert-success" data-am-alert>
  <button type="button" class="am-close">&times;</button>
  <p>
  <?php
  switch($_GET['suc']){
    case 10:
        echo "[2010]注册成功";
        break;
    default:
        echo "[XXXX]未知行为的成功";
  }?>
  </p>
</div>
<?php }?>
	<hr>
    <br>
    <br>
	<?php 
	if(isset($_session['username'])&&isset($_session['password'])){
		$username=$_session["username"];
        $password=$_session["password"];
$sql="select * from user where username='{$username}' and password='{$password}'";  
$rs=query($sql); 
$rom=mysqli_fetch_array($rs);
		if($password===$rom['password']){
	echo "
	<ul class=\"am-list confirm-list\" id=\"doc-modal-list\">
	<p>我们侦测到你已登录以下账号,使用它们登陆么?</p>
	<h2><a href=\"index.php\">{$username}</a></h2>
	</ul>
	";
	}
	}
	
	if(isset($_GET['reg'])){?>
    <form method="post" action="login.php?reg" class="am-form">
      <label for="email">用户名:</label>
      <input type="text" name="username" id="username" value="">
      <br>
      <label for="password">密码:</label>
      <input type="password" name="password" id="password" value="">
      <br>
	    <label for="password">重复密码:</label>
      <input type="password" name="cpassword" id="cpassword" value="">
      <br>
      <br />
      <div class="am-cf">
        <input type="submit" name="" value="注册" class="am-btn am-btn-primary am-btn-sm am-fl">
		<button type='button'  onclick="javascript:window.location.href='login.php'" class='am-btn am-btn-success'>登录</button>
       
      </div>
    </form>
	<?php }else{?>
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
		<button type='button'  onclick="javascript:window.location.href='login.php?reg'" class='am-btn am-btn-success'>注册</button>
       
      </div>
    </form>
	<?php }?>
    <hr>
    <p>© Power By 7gugu.</p>
  </div>
</div>
</body>

</html>
