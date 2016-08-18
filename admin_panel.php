<?php 
require 'function/corestart.php';
checkuser();
if(isset($_SESSION['sec'])){
	if($_SESSION['sec']<1){
		header("Location: index.php?f=4");
		exit();
	}
}else{
	header("Location: index.php?f=4");
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
header("Location: admin_panel.php?inser");
exit();
}
//激活码删除
if(isset($_GET['inser'])&&isset($_GET['dels'])){
	$dels=$_GET['dels'];
	query("delete from inser where id='{$dels}'");
	$row=mysqli_affected_rows();
if($row>0)
{
	header("Location: admin_panel.php?inser&s=4");
	exit();
}else{
header("Location: admin_panel.php?inser&f=4");
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
	header("Location: admin_panel.php?inser&s=4");
	exit();
}else{
header("Location: admin_panel.php?inser&f=4");
exit();
}
}
if(isset($_GET['update'])&&isset($_POST['password'])){
	$npassword=$_POST['password'];
	query("update user set password='{$npassword}'where id='{$_POST['id']}'");
	$row=mysqli_affected_rows($connect);
if($row>0)
{
	header("Location: admin_panel.php?muser&s=4");
}else{
header("Location: admin_panel.php?muser&f=4");
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
   <?php 
   if(isset($_GET['inser'])){
	   echo "    <div class='am-g'> 
        <div class='am-u-sm-8'>
			  <form method='POST' class='am-form' action='admin_panel.php?cinser'>
		<fieldset>
    <legend>生成激活码</legend>
	  <div class='am-form-group'>
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
</div>";
 if(isset($_GET['fr'])){
  echo "<p><font color='red'>秘钥失效或不存在</font></p>";
  }
echo "<br>
<button type='submit' class='am-btn am-btn-success'>保存秘钥</button>
  </form>
  </div>
</section>
<section class='am-panel am-panel-default'>
  <header class='am-panel-hd'>
    <h3 class='am-panel-title'>激活计划任务</h3>
  </header>
  <div class='am-panel-bd'>
     <div class='am-form-group'>
	 <h3>状态</h3>
	 <p class='am-article-meta'>由于计划任务为脚本,需要手动激活才可使用,除非关闭服务器,否则脚本将会以最小的资源占用一直运行<p>
  <button type='submit'  onclick=\"javascript:window.location.href='admin_panel.php?scron'\" class='am-btn am-btn-warning'>激活</button>
  </div>
  </div>
</section>
</div>
<div class='am-u-sm-6'>
<section class='am-panel am-panel-default'>
  <header class='am-panel-hd'>
    <h3 class='am-panel-title'>任务执行时间";
	$row=mysqli_fetch_array(query("select * from cron where name='cron'"));
	if($row['switch']==0){echo "<font color='red'>[已禁用]</font>";}else{echo "<font color='green'>[已启用]</font>";}
	$time=$row['time']/3600;
	echo "</h3>
  </header>
  <div class='am-panel-bd'>
     <div class='am-form-group'>
	 <h3>状态</h3>
     <div class='am-input-group'>
	 <span class='am-input-group-label'>循环时间</span>
	 <form method='POST' action='admin_panel.php'>
  <input type='text' name='time'  class='am-form-field' value='{$time}'>
  </div>
  <p class='am-article-meta'>留空则不启用计划任务,输入框填隔多少小时执行一次任务</p>
  <button type='submit' class='am-btn am-btn-secondary'>保存</button>
    </form></div>
  </div>
</section>
</div>
<div class='am-u-sm-6'>
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
  </div>         
        </div>
      </div>
    </div>
   ";
   }elseif(isset($_GET['notice'])){
	    echo " 
      <div class='am-g'>
        <div class='am-u-sm-12'>
        <div class='am-article-hd'>
    <h1 class='am-article-title'>敬请期待[公告]</h1>
  </div>         
        </div>
      </div>
    </div>
   ";
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
