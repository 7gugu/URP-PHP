<?php 
require 'function/corestart.php';
checkuser();
if(isset($_SESSION['sec'])){
	if($_SESSION['sec']<1){
		header("Location: index.php?err=9");
		exit();
	}
}else{
	header("Location: index.php?err=9");
	exit();
}
$v="a";
if(isset($_GET['cinser'])&&isset($_POST['time'])){
	$time=$_POST['time'];
	$num=$_POST['num'];
	for($i=0;$i<=$num;$i++){
			$uid = query("select * from inser order by id DESC limit 1 ");
	$uid=mysqli_fetch_array($uid);
	if($uid){
		$uid=$uid['id'];
	}else{
	$uid=0;
	}
	$uid++;
	$inser=getinser(8);
	$inserpassword=getinser(8);
	$sql="insert into inser(id,password,time,inser)values('$uid','$inserpassword','$time','$inser')";
	query($sql);	
}
header("Location: admin_panel.php?inser&suc=13");
exit();
}
//激活码删除
if(isset($_GET['inser'])&&isset($_GET['dels'])){
	$dels=$_GET['dels'];
	query("delete from inser where id='{$dels}'");
	$row=mysqli_affected_rows($connect);
if($row>0)
{
	header("Location: admin_panel.php?inser&suc=14");
	exit();
}else{
header("Location: admin_panel.php?inser&err=21");
exit();
}
}
//管理用户
if(isset($_GET['muser'])){
	if(isset($_GET['dels'])){
	$dels=$_GET['dels'];
	query("delete from user where id='{$dels}'");
	$row=mysqli_affected_rows($connect);
if($row>0)
{
	$urs=query("select count(*) from server where id='{$dels}'");
	$mfa=mysqli_fetch_array($urs);
	if($mfa[0]!=false){
		query("delete from server where id='{$dels}'");
	}
	header("Location: admin_panel.php?muser&suc=15");
	exit();
}else{
header("Location: admin_panel.php?muser&err=21");
exit();
}
}
if(isset($_GET['update'])&&isset($_POST['password'])){
	$npassword=$_POST['password'];
	query("update user set password='{$npassword}'where id='{$_POST['id']}'");
	$row=mysqli_affected_rows($connect);
if($row>0)
{
	header("Location: admin_panel.php?muser&suc=8");
}else{
header("Location: admin_panel.php?muser&err=21");
}
}
}
//启动rocket update
if(isset($_GET['stac'])){
	query("update cron set switch='1'where name='rocket'");
	header("Location: admin_panel.php?cron");
}
if(isset($_GET['stoc'])){
	query("update cron set switch='0'where name='rocket'");
	header("Location: admin_panel.php?cron");
}
if(isset($_GET['checkr'])&&isset($_POST['key'])){
	$key=$_POST['key'];
	$check=check_key($key);
	if($check){
query("UPDATE `cron` SET `key`='{$key}' WHERE `name`='rocket'");
	header("Location: admin_panel.php?cron");
}else{
	header("Location: admin_panel.php?cron&fr");
}
}
if(isset($_POST['time'])){
	if($_POST['time']==""||$_POST['time']==0){
		query("update cron set switch='0'where name='cron'");
		query("update cron set time=''where name='cron'");
	}else{
		$time=$_POST['time'];
		$time=$time*3600;
		query("update cron set switch='1'where name='cron'");
		query("update cron set time='{$time}'where name='cron'");
	}
	header("Location: admin_panel.php?cron");
	
}
if(isset($_GET['satime'])){
	query("update cron set switch='1'where name='time'");
	header("Location: admin_panel.php?cron");
}
if(isset($_GET['sotime'])){
	query("update cron set switch='0'where name='time'");
	header("Location: admin_panel.php?cron");
}
if(isset($_GET['scron'])){
	header("Location: do.php?cron");
}
//公告管理
if(isset($_GET['notice'])){
	if(isset($_GET['dels'])){
	$dels=$_GET['dels'];
	query("delete from notice where id='{$dels}'");
	$row=mysqli_affected_rows($connect);
if($row>0)
{
	header("Location: admin_panel.php?notice&suc=16");
}else{
header("Location: admin_panel.php?notice&err=21");
}
}
if(isset($_GET['add'])&&isset($_POST['text'])){
	$text=$_POST['text'];
	$uid = query("select * from notice order by id DESC limit 1 ");
	$uid=mysqli_fetch_array($uid);
	if($uid){
		$uid=$uid['id'];
	}else{
	$uid=0;
	}
	$uid++;
	query("insert into notice(id,text)values('$uid','$text')");
	$row=mysqli_affected_rows($connect);
if($row>0)
{
	header("Location: admin_panel.php?notice&suc=17");
}else{
header("Location: admin_panel.php?notice&err=21");
}
}
if(isset($_GET['update'])&&isset($_POST['text'])){
	$text=$_POST['text'];
	query("update notice set text='{$text}'where id='{$_POST['id']}'");
	$row=mysqli_affected_rows($connect);
if($row>0)
{
	header("Location: admin_panel.php?notice&suc=18");
}else{
header("Location: admin_panel.php?notice&err=21");
}
}
}
//插件管理
if(isset($_GET['mplugin'])){
	if(isset($_GET['dels'])){
		$del=$_GET['dels'];
		query("delete from plugin where name='{$del}'");
	$row=mysqli_affected_rows($connect);
if($row>0)
{   
    if(del("plugins/".$del)){ 
	header("Location: admin_panel.php?mplugin&suc=19");
}else{
	header("Location: admin_panel.php?mplugin&err=21");
}
}else{
header("Location: admin_panel.php?mplugin&err=21");
}
	}
	if(isset($_GET['update'])&&isset($_POST['text'])){
	$text=$_POST['text'];
	query("update plugin set state='{$text}'where name='{$_POST['name']}'");
//	echo "update plugin set state='{$text}'where name='{$_POST['name']}'";
	$row=mysqli_affected_rows($connect);
//	echo $row;exit();
if($row>0)
{
	header("Location: admin_panel.php?mplugin&suc=20");
}else{
header("Location: admin_panel.php?mplugin&err=21");
}
}
if(isset($_GET['upload'])&&isset($_FILES['upfile'])){
	if(upplugin($_FILES['upfile'])){
	header("Location: admin_panel.php?mplugin&suc=21");
}else{
header("Location: admin_panel.php?mplugin&err=21");
	}
}
}
//rocket update
if(isset($_GET['rocket'])){
    set_time_limit(0);
$rocket=mysqli_fetch_array(query("select * from cron where name='rocket'"));
	$rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
	if($rows['state']==1){
	$port=$rows['port']+1;
exec("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d");
 }
query("update server set state='0'where port='{$rows['port']}'");
}
rocket_download($rocket['key']);
getzip(PATHS."/Rocket.zip",PATHS."/unturned_data/Managed/");
$rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
	    $rows = mysqli_fetch_array (query("select * from server where port='{$rows['port']}'"));
		if($rows!=false){
	   rcon($rows['sid'],0,1935,'');
	  query("update server set state='1'where port='{$rows['port']}'");
		}
}
header("Location: admin_panel.php?cron&suc=24");
}
//game update
if(isset($_GET['game'])){
	$roms=mysqli_fetch_array(query("select * from cron where name='cmdpath'"));
	if(isset($_POST['cmduser'])){
		$cmduser=$_POST['cmduser'];
	    query("update cron set `key`='{$cmduser}' where `name`='cmduser'");
	    $row_user=mysqli_affected_rows($connect);
	}
	if(isset($_POST['cmdpaw'])){
		$cmdpaw=$_POST['cmdpaw'];
	    query("update cron set `key`='{$cmdpaw}' where `name`='cmdpaw'");
	    $row_paw=mysqli_affected_rows($connect);
	}
	if(isset($_POST['cmdpath'])){
		$cmdpath=$_POST['cmdpath'];
		if(file_exists($cmdpath."\\steamcmd.exe")){
			$cmdpath=str_ireplace("\\","/",$cmdpath);
	    query("update cron set `key`='{$cmdpath}' where `name`='cmdpath'");
	    $row_path=mysqli_affected_rows($connect);
	}else{
		$row_path="error";
	}
	if($cmdpath==""){
		query("update cron set `key`='' where `name`='cmdpath'");
	    $row_path=mysqli_affected_rows($connect);
	}
	
	}
	echo $row_path;
	echo $row_user;
	echo $row_paw;
	if($row_user>0&&$row_paw>0&&$row_path!="error"){
		if($row_path>0){
			query("update cron set `switch`='1' where `name`='cmdpath'");
	    $row=mysqli_affected_rows($connect);
		if($row>0){
			header("Location: admin_panel.php?cron&suc");
		}else{
			header("Location: admin_panel.php?cron&err1");
		}	
		}elseif($row_path=="error"){
			header("Location: admin_panel.php?cron&err&path");
		}else{
			header("Location: admin_panel.php?cron&err");
	}
	}elseif($row_user==0||$row_paw==0||$row_path==0){
		if($cmduser!=""&&$cmdpaw!=""&&$cmdpath!=""){
			query("update cron set `switch`='1' where `name`='cmdpath'");
		}
		header("Location: admin_panel.php?cron&suc");
	}else{
		header("Location: admin_panel.php?cron&err1");
	}
}
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>URP | 系统管理</title>
  <meta name="description" content="系统管理">
  <meta name="keywords" content="list">
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
 <!-- header start -->
<?php require 'function/header.php';?>
  <!-- header end -->
  
<div class="am-cf admin-main">
  <!-- sidebar start -->
<?php require 'function/sidebar.php';?>
  <!-- sidebar end -->

  <!-- content start -->
   <div class="admin-content">
     <div class='admin-content-body'>
      <div class='am-cf am-padding am-padding-bottom-0'>
        <div class='am-fl am-cf'><strong class='am-text-primary am-text-lg'>管理系统</strong> / <small>System Manage</small></div>
      </div>
      <hr>
	  <div class="am-u-sm-6">
<?php if(isset($_GET['err'])){
msg($_GET['err']);
	}
	if(isset($_GET['suc'])){	
msg($_GET['suc'],1);
 }?>
	</div>
   <?php 
   if(isset($_GET['inser'])){
	   echo "    <div class='am-g'> 
        <div class='am-u-sm-8'>
			  <form method='POST' class='am-form' action='admin_panel.php?cinser'>
		<fieldset>
    <legend>生成激活码</legend>
	  <div class='am-form-group'data-am-selected>
      <label for='doc-select-1'>生成数量</label>
      <select name='num' id='doc-select-1'>
        <option value='5'>5个</option>
        <option value='10'>10个</option>
        <option value='20'>20个</option>
      </select>
      <span class='am-form-caret'></span>
    </div>
	 <div class='am-form-group'>
      <label for='doc-select-1'>可用时间</label>
      <select name='time' id='doc-select-1'>
        <option value='1'>一天[1]</option>
        <option value='7'>一周[7]</option>
        <option value='30'>一个月[30]</option>
        <option value='90'>一个季度[90]</option>
		<option value='183'>半年[183]</option>
		<option value='365'>一年[365]</option>
      </select>
      <span class='am-form-caret'></span>
    </div>
	<hr>
<button type='submit' class='am-btn am-btn-warning' >生成激活码</button>
  </fieldset>
</form>
          </div>
        </div>

      <div class='am-g'>
        <div class='am-u-sm-12'>
          <form class='am-form'>
            <table class='am-table am-table-striped am-table-hover table-main'>
              <thead>
              <tr>
                <th class='table-check'><input type='checkbox' /></th><th class='table-id'>ID</th><th class='table-title'>激活码</th><th class='table-type'>激活密码</th><th class='table-date am-hide-sm-only'>可用时间</th><th class='table-set'>操作</th>
              </tr>
              </thead>
              <tbody>";
$perNumber=10;
@$page=$_GET['page'];
$count=query('select count(*) from inser');
$rs=mysqli_fetch_array($count); 
$totalNumber=$rs[0];
$totalPage=ceil($totalNumber/$perNumber);
if (!isset($page)) {
 $page=1;
}
$startCount=($page-1)*$perNumber;
	$result=query("select * from inser limit $startCount,$perNumber"); 
$row = mysqli_fetch_array( query("SELECT COUNT(*) FROM inser") );
if($row[0]==0){
	echo "
	 <tr>
                <td><input type='checkbox' /></td>
                <td>0</td>
                <td></td>
                <td></td>
                <td class='am-hide-sm-only'>暂无激活码可管理</td>
                <td class='am-hide-sm-only'></td>
                <td>
                </td>
              </tr>";
}else{
while($row = mysqli_fetch_array($result))
{
	
           echo "   <tr>
                <td><input type='checkbox' /></td>
<td> {$row['id']}</td>
                <td><a href='#'> {$row['inser']}</a></td>
<td><a href='#'>{$row['password']}</a></td>
                <td class='am-hide-sm-only'>{$row['time']}</td>
                <td>
                  <div class='am-btn-toolbar'>
				  
                    <div class='am-btn-group am-btn-group-xs'>
<button class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'type='button' onclick=\"javascript:window.location.href='admin_panel.php?inser&dels={$row['id']}'\"><span class='am-icon-trash-o'></span>删除</button>		
					</div>
                  
				  </div>
                </td>
              </tr>";
 }	
} 
              echo "</tbody>
            </table>
            <div class='am-cf'>
   共{$totalNumber}条记录
              <div class='am-fr'>
                <ul class='am-pagination'>
				";
if ($page != 1) { 

         echo "        <li><a href='admin_panel.php?inser&page=";
		 echo $page-1;
		 echo"'>«</a></li>";
		 }else{
 echo "<li class='am-disabled'><a href='admin_panel.php?inser&page=";
 echo $page-1;
 echo "'>«</a></li>"; 
 }			  
if ($page<$totalPage) {
           echo "       <li><a href='admin_panel.php?inser&page=";echo $page+1;echo "'>»</a></li>
 ";
 }else{
echo "<li class='am-disabled'><a href='admin_panel.php?inser&page=";echo $page+1;echo "'>»</a></li>
 ";}
             echo "   </ul>
              </div>
            </div>
         
        </div>
</div>
      </div>";
   }elseif(isset($_GET['muser'])){
	      echo "    <div class='am-g'> 
        <div class='am-u-sm-8'>
    <legend>管理用户</legend>
          </div>
        </div>

      <div class='am-g'>
        <div class='am-u-sm-12'>
          <form class='am-form' method='POST' action='admin_panel.php?muser&update'>
            <table class='am-table am-table-striped am-table-hover table-main'>
              <thead>
              <tr>
                <th class='table-check'><input type='checkbox' /></th><th class='table-id'>ID</th><th class='table-title'>用户名</th><th class='table-type'>密码</th><th class='table-date am-hide-sm-only'>邮箱</th><th class='table-set'>操作</th>
              </tr>
              </thead>
              <tbody>";
$perNumber=10;
@$page=$_GET['page'];
$count=query('select count(*) from user where admin=\'0\'');
$rs=mysqli_fetch_array($count); 
$totalNumber=$rs[0];
$totalPage=ceil($totalNumber/$perNumber);
if (!isset($page)) {
 $page=1;
}
$startCount=($page-1)*$perNumber;
	$result=query("select * from user where admin='0' limit $startCount,$perNumber"); 
$row = mysqli_fetch_array( query("SELECT COUNT(*) FROM user where admin='0'") );
if($row[0]==0){
	echo "
	 <tr>
                <td><input type='checkbox' /></td>
                <td>0</td>
                <td></td>
                <td></td>
                <td class='am-hide-sm-only'>暂无用户可管理</td>
                <td class='am-hide-sm-only'></td>
                <td>
                </td>
              </tr>";
}else{
while($row = mysqli_fetch_array($result))
{
	
           echo "   <tr>
                <td><input type='checkbox' /></td>
<td> {$row['id']}</td>
                <td><input id='usernmae' name='username' type='text' class='am-form-field' value='{$row['username']}' disabled>
			</td>
<td><input id='password' name='password' type='text' class='am-form-field' value='{$row['password']}' >
<input type='hidden' name='id' value='{$row['id']}'>
			</td>
                <td><input id='email' name='eamil' type='text' class='am-form-field' value='{$row['email']}' disabled>
			</td>
                <td>
                  <div class='am-btn-toolbar'>
				  
                    <div class='am-btn-group am-btn-group-xs'>
					  <button type='submit'class='am-btn am-btn-default am-btn-xs am-text-secondary' ><span class='am-icon-pencil-square-o'></span>保存更改</button>
<button class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'type='button' onclick=\"javascript:window.location.href='admin_panel.php?muser&dels={$row['id']}'\"><span class='am-icon-trash-o'></span>删除</button>		
					</div>
                  
				  </div>
                </td>
              </tr>";
 }	
} 
              echo "</tbody>
            </table>
            <div class='am-cf'>
   共{$totalNumber}条记录
              <div class='am-fr'>
                <ul class='am-pagination'>
				";
if ($page != 1) { 

         echo "        <li><a href='admin_panel.php?muser&page=";
		 echo $page-1;
		 echo"'>«</a></li>";
		 }else{
 echo "<li class='am-disabled'><a href='admin_panel.php?muser&page=";
 echo $page-1;
 echo "'>«</a></li>"; 
 }			  
if ($page<$totalPage) {
           echo "       <li><a href='admin_panel.php?muser&page=";echo $page+1;echo "'>»</a></li>
 ";
 }else{
echo "<li class='am-disabled'><a href='admin_panel.php?muser&page=";echo $page+1;echo "'>»</a></li>
 ";}
             echo "   </ul>
              </div>
            </div>
         
        </div>
</div>
      </div>";
   }elseif(isset($_GET['cron'])){
	   echo " 
      <div class='am-g'>
        <div class='am-u-sm-12'>
        <div class='am-article-hd'>
    <legend>计划任务[Beta]</legend>
	<div class='am-u-sm-6'>
	<section class='am-panel am-panel-default'>
  <header class='am-panel-hd'>
    <h3 class='am-panel-title'>Rocket更新</h3>
  </header>
  <div class='am-panel-bd'>
  <h4>状态</h4>";
  $row=mysqli_fetch_array(query("select * from cron where name='rocket'"));
  $on=$off="";
  if($row['switch']==1){
	  $on="disabled";
  }else{
	  $off="disabled";
  }
  echo "
    <button type='button' onclick=\"javascript:window.location.href='admin_panel.php?stac'\" class='am-btn am-btn-success'{$on}>启用</button>
<button type='button' onclick=\"javascript:window.location.href='admin_panel.php?stoc'\" class='am-btn am-btn-danger'{$off}>禁用</button>
<hr>
  <h4>Rocket秘钥</h4>
  <form class='am-form' method='POST' action='admin_panel.php?checkr'> 
    <div class='am-input-group'>
  <span class='am-input-group-label'><i class='am-icon-lock am-icon-fw'></i></span>
  <input type='text' name='key' class='am-form-field' placeholder='Rocket秘钥'";
  $row=mysqli_fetch_array(query("select * from cron where name='rocket'"));
  if($row['key']!==''){
  echo "value='{$row['key']}'";
  }
  echo ">
</div><br>";
if($row['key']!==''){
    echo "<a href='admin_panel.php?rocket'>立即更新Rocket</a><br>";
}
echo "
<br>
<button type='submit' class='am-btn am-btn-success'>保存秘钥</button>
  </form>
  </div>
</section>
<section class='am-panel am-panel-default'>
  <header class='am-panel-hd'>
    <h3 class='am-panel-title'>时间计数</h3>
  </header>
  <div class='am-panel-bd'>
     <div class='am-form-group'>
	 <h3>状态</h3>";
	 $dis=mysqli_fetch_array(query("select * from cron where name ='time'"));
	 $ton="";
	 $toff="";
	 if($dis['switch']==0){
		 $toff="disabled";
	 }else{
	 $ton="disabled";
	 }
	 echo "
  <h4 class='am-article-meta'>是否每天扣除服务器可用时间,过期5天后自动删除数据</h4>
  <button type='submit' onclick=\"javascript:window.location.href='admin_panel.php?satime'\" class='am-btn am-btn-secondary' {$ton}>激活</button>
  <button type='submit' onclick=\"javascript:window.location.href='admin_panel.php?sotime'\" class='am-btn am-btn-danger' {$toff}>禁用</button>
    </div>
  </div>
</section>
</div>
<div class='am-u-sm-6'>
<section class='am-panel am-panel-default'>
  <header class='am-panel-hd'>
    <h3 class='am-panel-title'>游戏更新</h3>
  </header>
  <div class='am-panel-bd'>
     <div class='am-form-group'>
	 <h3>状态</h3>
	  <h4 class='am-article-meta'>保存参数便会启用</h4>
 <strong>
 ";
 $rom=mysqli_fetch_array(query("select * from cron where name='cmdpath'"));
 if($rom['key']==""){
	 query("update cron set `switch`='0' where `name`='cmdpath'");
 }
 $rom=mysqli_fetch_array(query("select * from cron where name='cmduser'"));
  if($rom['key']==""){
	 query("update cron set `switch`='0' where `name`='cmdpath'");
 }
 $rom=mysqli_fetch_array(query("select * from cron where name='cmdpaw'"));
  if($rom['key']==""){
	 query("update cron set `switch`='0' where `name`='cmdpath'");
 }
 $rom=mysqli_fetch_array(query("select * from cron where name='cmdpath'"));
 if($rom['switch']==1){
	 echo "<font color='green'>[已激活]</font>";
 }else{
	 echo "<font color='red'>[已禁用]</font>";
 }
 echo "
 </strong>
 <hr>
	<h3>参数</h3>
	<form method='POST' action='admin_panel.php?game'>
	<input type='text' name='cmdpath' class='am-form-field' value='";
	$rom=mysqli_fetch_array(query("select * from cron where name='cmdpath'"));
	echo $rom['key'];
	echo "' placeholder='steamCMD的位置'>
	<br>
	<input type='text' name='cmduser' class='am-form-field' value='";
	$rom=mysqli_fetch_array(query("select * from cron where name='cmduser'"));
	echo $rom['key'];
	echo "' placeholder='steam用户名'>
	<br>
	<input type='text' name='cmdpaw' class='am-form-field' value='";
	$rom=mysqli_fetch_array(query("select * from cron where name='cmdpaw'"));
	echo $rom['key'];
	echo "' placeholder='steam密码'>
	<br>
	<input type='text' name='' class='am-form-field' placeholder='游戏的位置' value='";
	echo PATHS;
	echo "'disabled><br>
	<button type='submit' class='am-btn am-btn-success'>保存设置</button>
	</form>
	</div>
  </div>
</section>
</div>
  </div>         
        </div>
      </div>
    </div>
   ";
   }elseif(isset($_GET['notice'])){
	  echo "    <div class='am-g'> 
        <div class='am-u-sm-8'>
    <legend>管理公告</legend>
          </div>
        </div>
 <div class='am-u-sm-6'>
		  <form method='POST' action='admin_panel.php?notice&add'>
		  <fieldset>
	  <div class='am-form-group'>
      <label for='doc-select-1'>公告内容</label>
    <input id='text' name='text' type='text' class='am-form-field'>
    </div>
<button type='submit' class='am-btn am-btn-success' >添加公告</button>
  </fieldset>
		  </form>
		  </div>
		  
      <div class='am-g'>
        <div class='am-u-sm-12'>
		<hr>
          <form class='am-form' method='POST' action='admin_panel.php?notice&update'>
            <table class='am-table am-table-striped am-table-hover table-main'>
              <thead>
              <tr>
                <th class='table-check'><input type='checkbox' /></th><th class='table-id'>ID</th><th class='table-title'>文章</th><th class='table-set'>操作</th>
              </tr>
              </thead>
              <tbody>";
$perNumber=10;
@$page=$_GET['page'];
$count=query('select count(*) from notice');
$rs=mysqli_fetch_array($count); 
$totalNumber=$rs[0];
$totalPage=ceil($totalNumber/$perNumber);
if (!isset($page)) {
 $page=1;
}
$startCount=($page-1)*$perNumber;
	$result=query("select * from notice  limit $startCount,$perNumber"); 
$row = mysqli_fetch_array( query("SELECT COUNT(*) FROM notice ") );
if($row[0]==0){
	echo "
	 <tr>
                <td><input type='checkbox' /></td>
                <td>0</td>
                <td class='am-hide-sm-only'>暂无公告可管理</td>
                <td>
                </td>
              </tr>";
}else{
while($row = mysqli_fetch_array($result))
{
	
           echo "   <tr>
                <td><input type='checkbox' /></td>
<td> {$row['id']}</td>
                <td><input id='text' name='text' type='text' class='am-form-field' value='{$row['text']}' ><input name='id' type='hidden'  value='{$row['id']}' >   
                 </td>
				<td>
                  <div class='am-btn-toolbar'>
                    <div class='am-btn-group am-btn-group-xs'>
					  <button type='submit'class='am-btn am-btn-default am-btn-xs am-text-secondary' ><span class='am-icon-pencil-square-o'></span>保存更改</button>
<button class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'type='button' onclick=\"javascript:window.location.href='admin_panel.php?notice&dels={$row['id']}'\"><span class='am-icon-trash-o'></span>删除</button>		
					</div>
				  </div>
                </td>
              </tr>";
 }	
} 
              echo "</tbody>
            </table>
            <div class='am-cf'>
   共{$totalNumber}条记录
              <div class='am-fr'>
                <ul class='am-pagination'>
				";
if ($page != 1) { 

         echo "        <li><a href='admin_panel.php?notice&page=";
		 echo $page-1;
		 echo"'>«</a></li>";
		 }else{
 echo "<li class='am-disabled'><a href='admin_panel.php?notice&page=";
 echo $page-1;
 echo "'>«</a></li>"; 
 }			  
if ($page<$totalPage) {
           echo "       <li><a href='admin_panel.php?notice&page=";echo $page+1;echo "'>»</a></li>
 ";
 }else{
echo "<li class='am-disabled'><a href='admin_panel.php?notice&page=";echo $page+1;echo "'>»</a></li>
 ";}
             echo "   </ul>
              </div>
            </div>
         
        </div>
</div>
   </div>";
   }elseif(isset($_GET['mplugin'])){
	    echo "    <div class='am-g'> 
        <div class='am-u-sm-8'>
    <legend>管理插件</legend>
          </div>
        </div>
 <div class='am-u-sm-6'>
 <form class='am-form' action='admin_panel.php?mplugin&upload' enctype='multipart/form-data' method='post'>
      <input type='file' id='doc-ipt-file-1' name='upfile'>
      <p class='am-form-help'>请上传标准的dll文件</p>
	   <button type='submit' class='am-btn am-btn-warning'>上传/更新插件</button>
          </form><br>
		  </div>  
      <div class='am-g'>
        <div class='am-u-sm-12'>
		<hr>
            <table class='am-table am-table-striped am-table-hover table-main'>
              <thead>
              <tr>
                <th class='table-check'><input type='checkbox' /></th><th class='table-title'>插件</th><th class='table-title'>描述</th><th class='table-set'>操作</th>
              </tr>
              </thead>
              <tbody>";
		foreach(glob("plugins/*.dll",GLOB_BRACE) as $afile){
			$uid = query("select * from plugin order by id DESC limit 1 ");
	$uid=mysqli_fetch_array($uid);
	if($uid){
		$uid=$uid['id'];
	}else{
	$uid=0;
	}
	$uid++;
$a=str_replace('plugins/','',$afile);
  $cou=mysqli_fetch_array(query("select count(*) from plugin where name='{$a}'"));
if($cou[0]==0){
	query("insert into plugin(id,name,state)values('$uid','$a','')");
}
		}		
$perNumber=10;
@$page=$_GET['page'];
$count=query('select count(*) from plugin');
$rs=mysqli_fetch_array($count); 
$totalNumber=$rs[0];
$totalPage=ceil($totalNumber/$perNumber);
if (!isset($page)) {
 $page=1;
}
$startCount=($page-1)*$perNumber;
	$result=query("select * from plugin  limit $startCount,$perNumber"); 
$row = mysqli_fetch_array( query("SELECT COUNT(*) FROM plugin ") );
if($row[0]==0){
	echo "
	 <tr>
                <td><input type='checkbox' /></td>
                <td class='am-hide-sm-only'>暂无插件可管理</td>
                <td></td>
				<td></td>
              </tr>";
}else{
while($row = mysqli_fetch_array($result))
{
	
           echo "   
		    <form class='am-form' method='POST' action='admin_panel.php?mplugin&update'>
		   <tr>
                <td><input type='checkbox' /></td>
<td> {$row['name']}</td>
                <td><input id='text' name='text' type='text' class='am-form-field' value='{$row['state']}' ><input name='name' type='hidden'  value='{$row['name']}' >   
                 </td>
				<td>
                  <div class='am-btn-toolbar'>
                    <div class='am-btn-group am-btn-group-xs'>
					  <button type='submit'class='am-btn am-btn-default am-btn-xs am-text-secondary' ><span class='am-icon-pencil-square-o'></span>保存更改</button>
					  <button class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'type='button' onclick=\"javascript:window.location.href='admin_panel.php?mplugin&dels={$row['name']}'\"><span class='am-icon-trash-o'></span>删除</button>		
					</div>
				  </div>
                </td>
              </tr>
			   </form>
			  ";
 }	
} 
              echo "</tbody>
            </table>
            <div class='am-cf'>
   共{$totalNumber}条记录
              <div class='am-fr'>
                <ul class='am-pagination'>
				";
if ($page != 1) { 

         echo "        <li><a href='admin_panel.php?mplugin&page=";
		 echo $page-1;
		 echo"'>«</a></li>";
		 }else{
 echo "<li class='am-disabled'><a href='admin_panel.php?mplugin&page=";
 echo $page-1;
 echo "'>«</a></li>"; 
 }			  
if ($page<$totalPage) {
           echo "       <li><a href='admin_panel.php?mplugin&page=";echo $page+1;echo "'>»</a></li>
 ";
 }else{
echo "<li class='am-disabled'><a href='admin_panel.php?mplugin&page=";echo $page+1;echo "'>»</a></li>
 ";}
             echo "   </ul>
              </div>
            </div>
         
        </div>
</div>
   </div>";
   }else{
  echo " 
      <div class='am-g'>
        <div class='am-u-sm-12'>
        <div class='am-article-hd'>
    <h1 class='am-article-title'>首页</h1>
    <p>欢迎回来 {$_SESSION['username']},服务器运转正常[不然你怎么看到我Orz]</p>
  </div>
      
            <div class='am-cf'>
             
            </div>
          
        </div>

      </div>
    </div>
   ";}
	?>
	            <hr />
            <p>注：.....</p>
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