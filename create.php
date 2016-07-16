<?php 
require 'function/corestart.php';
require_once 'function/createpage.php';
checkuser();
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Ucon | manage</title>
  <meta name="description" content="服务器创建">
  <meta name="keywords" content="create">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="Amaze UI" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <link rel="stylesheet" href="assets/css/admin.css">
 
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->

<header class="am-topbar am-topbar-inverse admin-header">
  <div class="am-topbar-brand">
    <strong>Ucon2.0</strong> <small>后台管理</small>
  </div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-users"></span> 管理员 <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="#"><span class="am-icon-user"></span> 资料</a></li>
          <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>
          <li><a href="#"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
     </ul>
  </div>
</header>

<div class="am-cf admin-main">
  <!-- sidebar start -->
  <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
      <ul class="am-list admin-sidebar-list">
        <li><a href="index.php"><span class="am-icon-home"></span> 首页</a></li>
        <li><a href="list.php"><span class="am-icon-table"></span> 产品列表</a></li>
        <li><a href="wallet.php"><span class="am-icon-pencil-square-o"></span> 我的钱包</a></li>
        <li><a href="#"><span class="am-icon-sign-out"></span> 注销</a></li>
      </ul>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-bookmark"></span> 公告</p>
          <p>Ucon2.0全新版本</p>
        </div>
      </div>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-tag"></span> wiki</p>
          <p>Test</p>
        </div>
      </div>
    </div>
  </div>
  <!-- sidebar end -->

  <!-- content start -->
   <div class="admin-content">
    <div class="admin-content-body">
      <div class="am-u-md-8">
	   <br>
  	
<?php 
if(isset($_GET['c0'])){
echo $c0;	
}
if(isset($_GET['c1'])&&isset($_POST['inser'])){
	$inser=$_POST['inser'];
	$a=checkinser($inser);
	if($a[0]==false){
		header("Location:create.php?c0&err=7");//激活码不存在或错误
	}
	setcookie("inser", $inser, time()+600);
	echo $c1a;
	$rp=query("select * from map where state ='2'");
	while($row=mysql_fetch_array($rp)){
		?>
		<option><?php echo $row['name'];?></option>
	<?php
	}
	echo $c1b;
	echo "  <input id='' name='' type='text' class='' value='{$a[1]}' disabled> 
	<input id='time' name='time' type='hidden' class='' value='{$a[1]}' >";
	echo $c1c;
	
}
if(isset($_GET['c2'])&&isset($_POST['cheat'])){
	$username=$_SESSION['username'];
	$inser=$_COOKIE['inser'];
	$a=checkinser($inser);
	if($a[0]==false){
		header("Location:create.php?c0&err=7");//激活码不存在或错误
	}
	$arr=range(20000,30000);//端口分配范围,肯定够用
  shuffle($arr);
foreach($arr as $values)
{
  $query= "SELECT * FROM server WHERE port='{$values}'";
  $rs=query($query);
  $num=mysql_num_rows($rs);
  if($num){}else{
	  $port=$values;
	  break;
  }
}
$arr=range(2000,3000);//端口分配范围,肯定够用
  shuffle($arr);
foreach($arr as $values)
{
  $query= "SELECT * FROM server WHERE rport='{$values}'";
  $rs=query($query);
  $num=mysql_num_rows($rs);
  if($num){}else{
	  $rport=$values;
	  break;
  }
}
	$cheat=$_POST['cheat'];
	if($cheat=="off"){
		$cheat="";
	}else{
		$cheat="cheats";
	}
	$sname=$_POST['servername'];
	$map=$_POST['map'];
	$time=$_POST['time'];
	$dif=$_POST['dif'];
	$pv=$_POST['pv'];
		if(fcreate($sname,$port,$map,$dif,$pv,$cheat)==false){
			header("Location:create.php?c0&err=6");//写入基础文件失败
		}else{
			$rs=query("select * from user where username='{$username}'");
	        $rom=mysql_fetch_array($rs);
			$cou=$rom['scout']+1;
			query("update user set scout ='{$cou}'where username='{$username}'");
			$sec=1;
			if($_SESSION['sec']==1){
				$sec=2;
			}
			$uid = mysql_query("select * from server order by id DESC limit 1 ");
	$uid=mysql_fetch_array($uid);
	if($uid){
		$uid=$uid['id'];
	}else{
	$uid=0;
	}
	$uid++;
	$numbers = range(2000,3000);
shuffle($numbers);
foreach ($numbers as $number) {
    $num=$number;
	break;
}
$sid=$sname."#".$num;
	$q="insert into server(id,user,time,sec,rpw,rport,port,name,state,sid)values('$uid','$username','$time','$sec','123456','$rport','$port','$sname','0','$sid')";
			query($q);
			$numb=mysql_affected_rows();
			query("DELETE FROM inser WHERE inser='{$inser}'");
			if($numb==0){
				setcookie('query',$q,time()+3600*24*7);
				header("Location:create.php?c0&err=8");//sql写入失败
				exit();
			}
			echo $c2;
		}
}
?>

  </div>

      </div>
    </div>
    <footer class="admin-content-footer">
      <hr>
      <p class="am-padding-left">© 2016 Power By 7gugu.</p>
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
