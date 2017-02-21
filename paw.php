<?php 
require 'function/corestart.php';
checkuser();
$v="n";
if(isset($_POST['npw'])){
	if($_POST['opw']===$_SESSION['password']){
		if($_POST['npw']==$_POST['cpw']){
			query("update user set password='{$_POST['cpw']}' where username='{$_SESSION['username']}'");
			$rows=mysqli_affected_rows();
			if($rows>0){
			$_SESSION['password']=$_POST['cpw'];
				echo "<script>location.href='paw.php?suc=8';</script>"; 
	exit();
			}else{
			echo "<script>location.href='paw.php?err=7';</script>"; 
	exit();	
			}
		}else{
			echo "<script>location.href='paw.php?err=8';</script>"; 
	exit();
		}
	}else{
		echo "<script>location.href='paw.php?err=9';</script>"; 
	exit();
	}
}
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>URP | 更改密码</title>
  <meta name="description" content="产品列表">
  <meta name="keywords" content="list">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="更改密码" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <link rel="stylesheet" href="assets/css/admin.css">
 
</head>
<body>
<!-- header start -->
<?php require 'function/header.php';?>
  <!-- header end -->

<div class="am-cf admin-main">
  <!-- sidebar start -->
  <?php require 'function/sidebar.php';?>
  <!-- sidebar end -->

  <!-- content start -->
   <div class="admin-content">
    <div class="admin-content-body">
      <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">账户管理</strong> / <small>请谨慎操作</small></div>
      </div>
      <hr>
	  <script>
	  function check(){
   if(document.getElementById("opw").value=="")
    {
        alert("旧密码不可为空!");
        document.getElementById("opw").value.focus();
        return false;
     }
   if(document.getElementById("npw").value=="")
    {
        alert("新密码不可为空!");
       document.getElementById("npw").focus();
        return false;
     }
   if(document.getElementById("cpw").value=="")
    {
        alert("重复密码不可为空!");
       document.getElementById("cpw").focus();
		return false;
     }
	  if(document.getElementById("npw").value!=document.getElementById("cpw").value)
    {
        alert("重复密码不一致!");
       document.getElementById("npw").focus();
        return false;
     }
    return true;
	  }
	  </script>
<div class='am-u-lg-6'>
			<div class="am-panel am-panel-default">
			<div class="am-panel-hd">更改密码</div>
			<div class="am-panel-bd">
			<?php if(isset($_GET['err'])){
msg($_GET['err']);
	}
	if(isset($_GET['suc'])){	
msg($_GET['suc'],1);
 }?>
			 <div class="am-form-group">
			<form action="paw.php" method="post" onsubmit="return check();">
			 <input id='opw' class="am-form-field" name='opw' type='text'  placeholder='输入旧密码'><br>
			 <input id='npw' class="am-form-field" name='npw' type='text'  placeholder='输入新密码'><br>
			  <input id='cpw' class="am-form-field" name='cpw' type='text'  placeholder='重复输入新密码'><br>
			  <button type='submit'  class='am-btn am-btn-warning'>确认</button> 
			  </form>
			  </div>
			</div>
			</div>
			</div>
			</div>
    </div>
    <footer class="admin-content-footer">
      <hr>
      <p class="am-padding-left">© 2017 Power By 7gugu.</p>
    </footer>
  </div>
  <!-- content end -->

</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>
<script src="assets/js/app.js"></script>
</body>

</html>
