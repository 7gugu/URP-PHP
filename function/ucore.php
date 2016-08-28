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
function msg($num,$mode=0){
	if($mode==1){
		echo "<div class='am-alert am-alert-success' data-am-alert>
  <button type='button' class='am-close'>&times;</button>
  <p>";
   switch($_GET['suc']){
    case 1:
        echo "[2001]开服成功";
        break;
    case 2:
        echo "[2002]关服成功";
        break;
		 case 3:
        echo "[2003]重启成功";
        break;
		 case 4:
        echo "[2004]修改配置成功";
        break;
		 case 5:
        echo "[2005]控制台发送指令成功";
        break;
		 case 6:
        echo "[2006]添加插件成功";
        break;
		 case 7:
        echo "[2007]上传地图成功";
        break;
		 case 8:
        echo "[2008]修改密码成功";
        break;
		 case 9:
        echo "[2009]服务器删除成功";
        break;
		 case 10:
        echo "[2010]注册成功";
        break;
		case 11:
        echo "[2011]cron激活成功";
        break;
		case 12:
        echo "[2012]续费成功";
        break;
		case 13:
        echo "[2013]激活码生成成功";
        break;
		case 14:
        echo "[2014]激活码删除成功";
        break;
		case 15:
        echo "[2015]删除用户成功";
        break;
		case 16:
        echo "[2016]公告删除成功";
        break;
		case 17:
        echo "[2017]公告添加成功";
        break;
		case 18:
        echo "[2018]公告更新成功";
        break;
		case 19:
        echo "[2019]插件删除成功";
        break;
		case 20:
        echo "[2020]插件描述更新成功";
        break;
		case 21:
        echo "[2021]插件上传/更新成功";
        break;
        case 22:
        echo "[2022]MOD上传成功";
        break;
		case 23:
        echo "[2023]文件删除成功";
        break;
    default:
        echo "[XXXX]未知行为的成功";
  }
  
	}else{
echo "<div class='am-alert am-alert-warning' data-am-alert>
  <button type='button' class='am-close'>&times;</button>
  <p>";
  switch($_GET['err']){
        case 1:
        echo "[1001]Socket创建失败";
        break;
        case 2:
        echo "[1002]Socket连接失败";
        break;
		case 3:
        echo "[1003]管理服务器失败";
        break;
		case 4:
        echo "[1004]数据获取失败";
        break;
		case 5:
        echo "[1005]添加插件失败";
        break;
		case 6:
        echo "[1006]上传地图失败";
        break;
		case 7:
        echo "[1007]修改密码失败";
        break;
        case 8:
        echo "[1008]重复密码有误";
        break;
		case 9:
        echo "[1009]权限错误";
        break;
		 case 10:
        echo "[1010]Session失效";
        break;
		 case 11:
        echo "[1011]服务器数据同步失败";
        break;
		 case 12:
        echo "[1012]服务器删除失败";
        break;
        case 13:
        echo "[1013]激活码错误或错误";
        break;
		case 14:
        echo "[1014]基础文件读写失败";
        break;
		 case 15:
        echo "[1015]注册失败";
        break;
        case 16:
        echo "[1016]信息缺失,无法注册";
        break;
		case 17:
        echo "[1017]重复密码不相同";
        break;
		case 18:
        echo "[1018]用户名已被注册";
        break;
		case 19:
        echo "[1019]邮箱已被注册";
        break;
		case 20:
        echo "[1020]用户名或密码有误";
        break;
		case 21:
        echo "[1021]数据库执行失败";
        break;
        case 22:
        echo "[1022]服务器名字长度不足(需大于5个字)";
        break;
        case 23:
        echo "[2023]MOD上传失败";
        break;
    default:
        echo "[XXXX]出现了一个未知错误,请尽快联系管理员解决";
  }
	}
	 echo "</p>
</div>";
}
		/*
		Ucon 2.0 user core
		Power by 7gugu
		*/
?>