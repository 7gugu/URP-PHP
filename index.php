<?php 
require 'function/corestart.php';
checkuser();
$v="n";
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>URP | 首页</title>
  <meta name="description" content="index">
  <meta name="keywords" content="index">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="首页" />
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
      <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">后台首页</strong> / <small>状态总览</small></div>
      </div>

      <ul class="am-avg-sm-1 am-avg-md-3 am-margin am-padding am-text-center admin-content-list ">
        <li><a href="list.php" class="am-text-success"><span class="am-icon-btn am-icon-server"></span><br/>服务器管理<br/></a></li>
        <li><a href="create.php?c0" class="am-text-warning"><span class="am-icon-btn am-icon-usd"></span><br/>创建服务器<br/></a></li>
        <li><a href="http://www.7gugu.com" class="am-text-danger"><span class="am-icon-btn  am-icon-file-text"></span><br/>作者博客<br/></a></li>
      </ul>
        <div class="am-u-md-12">
		<?php if(isset($_GET['err'])){
msg($_GET['err']);
	}
	if(isset($_GET['suc'])){	
msg($_GET['suc'],1);
 }?>
          <div class="am-panel am-panel-default">
            <div class="am-panel-hd am-cf">公告</div>
            <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
              <ul class="am-list admin-content-task">
                <li>
                  <div class="admin-task-meta"> Posted  by admin</div>
                  <div class="admin-task-bd">
                   <?php
                 $notice=mysqli_fetch_array(query("select * from notice order by rand() limit 1"));
				 if($notice!=""){
				echo $notice['text'];
				 }else{
					 echo "服务器运作正常";
				 }
				   ?>
                  </div>
                </li>
              </ul>
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
<?php 
if(isset($_COOKIE['wel'])){
		setcookie("wel","",time()-9*365);
		echo "<script>alert('welcome back {$_SESSION['username']}!')</script>";
	}
?>
<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
