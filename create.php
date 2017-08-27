<?php 
require 'function/corestart.php';
require_once 'function/createpage.php';
checkuser();
$v="n";
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo HNAME;?> | 创建服务器</title>
  <meta name="description" content="服务器创建">
  <meta name="keywords" content="create">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="URP" />
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
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">创建服务器</strong> / <small>请谨慎操作</small></div>
      </div>
	 <?php if(isset($_GET['err'])){
msg($_GET['err']);
	}
	if(isset($_GET['suc'])){	
msg($_GET['suc'],1);
 }?>
	  <hr>
      <div class="am-u-md-8">
	   <br>
  	
<?php 
if(isset($_GET['c0'])){
echo $c0;	
}elseif(isset($_GET['c1'])&&isset($_POST['inser'])&&isset($_POST['inserpassword'])){
	$inser=$_POST['inser'];
	$inserpassword=$_POST['inserpassword'];
	$a=checkinser($inser,$inserpassword);
	if($a[0]==false){
		header("Location:create.php?c0&err=13");//激活码不存在或错误
	exit();
	}
	$query= "SELECT * FROM inser WHERE password='{$inserpassword}' and inser='{$inser}'";
    $rs=mysqli_fetch_array(query($query));
	setcookie("inser", $inser, time()+600);
	setcookie("inserpassword", $inserpassword, time()+600);
	echo $c1a1;
	echo " <input id='players' name='players' type='text' class='' value='{$rs['max']}' disabled>";
	echo $c1a2;
		gfl(1);
	echo $c1b;
	echo "  <input id='' name='' type='text' class='' value='{$a[1]}' disabled> ";
	echo $c1c;
	
}elseif(isset($_GET['c2'])&&isset($_POST['cheat'])){
	$inserpassword=$_COOKIE['inserpassword'];
	$inser=$_COOKIE['inser'];
	$a=checkinser($inser,$inserpassword);
	if($a[0]==false){
		header("Location:create.php?c0&err=13");//激活码不存在或错误
	}
	$arr=range(20000,30000);//端口分配范围,肯定够用
  shuffle($arr);
foreach($arr as $values)
{
  $query= "SELECT * FROM server WHERE port='{$values}'";
  $rs=query($query);
  $num=mysqli_num_rows($rs);
  if($num==false){
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
  $num=mysqli_num_rows($rs);
  if($num==false){
	  $rport=$values;
	  break;
  }
}
	$cheat=$_POST['cheat'];
	if($cheat=="off"){
		$ch=0;
		$cheat="cheats disable";
	}else{
		$ch=1;
		$cheat="cheats enable";
	}
	$sname=$_POST['servername'];
	if(strlen($sname)<=5){
		header("Location:create.php?c0&err=22");
	}
	$map=$_POST['map'];
	$arr=mysqli_fetch_array(query("select * from inser where inser='{$inser}'"));
	$time=$arr['time'];
	$dif=$_POST['dif'];
	$pv=$_POST['pv'];
	$view=$_POST['view'];
	$players=$arr['max'];
	if($players==0||$players==''){
		$players=1;
	}
		$numbers = range(2000,3000);
shuffle($numbers);
foreach ($numbers as $number) {
    $num=$number;
	break;
}
$rpw=getinser(8);
$sid=$_SESSION['username']."x".$num;
		if(fcreate($sname,$port,$rport,$rpw,$map,$dif,$pv,$cheat,$sid,$players)==false){
			header("Location:create.php?c0&err=14");//写入基础文件失败
		}else{
			$uid = query("select * from server order by id DESC limit 1 ");
	$uid=mysqli_fetch_array($uid);
	if($uid){
		$uid=$uid['id'];
	}else{
	$uid=0;
	}
	$uid++;
	$username=$_SESSION['username'];
	if(CSQL){
	csql("un_".$num,"un_".$num,md5("un_".$num));
    $dbname=$sqluser="un_".$num;
    $sqlpass=md5("un_".$num);
	$q="insert into server(id,user,time,rpw,rport,port,name,state,sid,players,welcome,difficult,mode,map,password,view,cheat,loadout,dbname,sqluser,sqlpaw)values('$uid','$username','$time','$rpw','$rport','$port','$sname','0','$sid','$players','本服务器由URP强力驱动','$dif','$pv','$map','','$view','$ch','','$dbname','$sqluser','$sqlpass')";
	recurse_copy("Libraries",PATHS."\Servers\\$sid\\Rocket\Libraries");
	}else{
	$q="insert into server(id,user,time,rpw,rport,port,name,state,sid,players,welcome,difficult,mode,map,password,view,cheat,loadout,dbname,sqluser,sqlpaw)values('$uid','$username','$time','$rpw','$rport','$port','$sname','0','$sid','$players','本服务器由URP强力驱动','$dif','$pv','$map','','$view','$ch','','','','')";
	}
		//echo $q;
			$r=query($q);
			$numb=mysqli_affected_rows($connect);
			if($numb<=0){
				//setcookie('query',$q,time()+3600*24);
				header("Location:create.php?c0&err=11");//sql写入失败
				exit();
			}else{
				query("DELETE FROM inser WHERE inser='{$inser}'");
			}
			echo $c2;
		}
}else{
	echo "
	<br>
<div class='am-g'>
  <div class='am-u-sm-6 am-u-lg-centered'>
  <div class=' am-alert am-alert-warning' data-am-alert>
  <h3>检测到非法跳转</h3>
  <p>请从正常页面的侧边栏创建服务器</p>
  <ul>
    <li>如果您是非法跳转，你可以戳这<a href='index.php'><code>返回首页</code></a></li>
    <li>如果您是正常访问，请寻求管理员寻求帮助</li>
  </ul>
</div>
  </div>
</div>
	";
}
?>

  </div>

      </div>
    </div>
    <footer class="admin-content-footer">
      <hr>
      <p class="am-padding-left">© <?php echo date("Y"); ?> Power By 7gugu.</p>
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
