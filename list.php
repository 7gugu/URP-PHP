<?php 
require 'function/corestart.php';
checkuser();
$v="n";
if(isset($_COOKIE['ser'])){
	setcookie('ser','',time()-999999);
}
if(isset($_GET['dels'])&&isset($_GET['id'])){
	$sid=$_GET['id'];
	$rows=mysqli_fetch_array(query("select * from server where sid='{$sid}'"));
$port=$rows['port']+1;
system("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d");
$ok=ddf(PATHS."//Servers//{$sid}//");
if($ok){
	query("delete from server where sid='{$sid}'");
	$row=mysqli_affected_rows($connect);
if($row>0)
{
	header("Location: list.php?s=4");//4删除成功
}else{
header("Location: list.php?f=4");
}
}else{
header("Location: list.php?f=5");//5文件夹删除失败
}
}
if(isset($_GET['code'])&&isset($_POST['inser'])&&isset($_POST['inserpassword'])){
	$inser=$_POST['inser'];
	$ser=$_GET['code'];
	$inserpassword=$_POST['inserpassword'];
	$a=checkinser($inser,$inserpassword);
	if($a[0]==false){
		header("Location:list.php?err=13");//激活码不存在或错误
	}else{
		$rows=mysqli_fetch_array(query("select * from server where sid='{$ser}' "));
		if($rows['time']<=0){
		$time=0+$a[1];
		}else{
			$time=$rows['time']+$a[1];
		}
		query("update server set time='{$time}'where sid='{$ser}'");
		query("delete from inser where inser='{$inser}'");
		header("Location:list.php?suc=12");
	}
	
}
if(isset($_GET['udate'])&&isset($_POST['date'])){
$date=$_POST['date'];
if($date==""){
	$date=0;
}
$sid=$_GET['udate'];
	query("update server set time='{$date}'where sid='{$sid}'");
    $row=mysqli_affected_rows($connect);
	if($row>0){
		header("Location:list.php?suc=25");
	}else{
		header("Location:list.php?err=24");
	}
	}
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo HNAME;?> | 产品列表</title>
  <meta name="description" content="产品列表">
  <meta name="keywords" content="list">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
  <link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="URP" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css?v=1.0"/>
  <link rel="stylesheet" href="assets/css/admin.css?v=1.0">
 
</head>
<body>
 <!-- header start -->
<?php require 'function/header.php';?>
  <!-- header end -->
  
<div class="am-cf admin-main">
  <!-- sidebar start -->
<?php require 'function/sidebar.php'; ?>
  <!-- sidebar end -->

  <!-- content start -->
   <div class="admin-content">
    <div class="admin-content-body">
      <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">产品列表</strong> / <small>购买后的服务器都会在此显示</small></div>
      </div>
<div class="am-u-sm-6">
<?php if(isset($_GET['err'])){
msg($_GET['err']);
	}
	if(isset($_GET['suc'])){	
msg($_GET['suc'],1);
 }?>
	</div><br>

      <hr>

      <div class="am-g"> 
        <div class="am-u-sm-12 am-u-md-3">
          <div class="am-input-group am-input-group-sm">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button" onclick="javascript:window.location.href='create.php?c0'">创建新的服务器</button>
          </span></div></div><br><hr>
		   <div class="am-u-sm-12">
		  <?php
		  if(isset($_GET['renew'])){
			  $sid=$_GET['renew'];
			  $row=mysqli_fetch_array(query("select * from server where sid='{$sid}'"));
			  
			  ?>
		  <div class="am-u-sm-6">
		  <form method="POST" action="list.php?code=<?php echo $sid;?>" onsubmit='return check(this)'>
		   <script type='text/javascript'>
function check(form){
if(form.inser.value==''){
alert('激活码不能为空！');
form.inser.focus();
return false;
}
if(form.inserpassword.value==''){
alert('激活密码不能为空！');
form.inserpassword.focus();
return false;
}
return true;
}
    </script>
		  <fieldset>
    <legend>续费[<?php echo $row['name']?>]服务器</legend>
	  <div class='am-form-group'>
      <label for='doc-select-1'>激活码</label>
    <input id='inser' name='inser' type='text' class='am-form-field'>
    </div>
	 <div class='am-form-group'>
       <label for='doc-select-1'>激活密码</label>
    <input id='inserpassword' name='inserpassword' type='text' class='am-form-field'>
    </div>
	<hr>
<button type='submit' class='am-btn am-btn-success' >续费</button>
  </fieldset>
		  </form>
		  </div>
		  <?php if($_SESSION['sec']==1){ ?>
		  <div class="am-u-sm-6">
		  <form method="POST" action="list.php?udate=<?php echo $sid;?>" onsubmit='return check(this)'>
		   <script type='text/javascript'>
function check(form){
if(form.date.value==''){
alert('时间不能为空！');
form.date.focus();
return false;
}
return true;
}
    </script>
		  <fieldset>
    <legend>修改[<?php echo $row['name']?>]服务器可用时间</legend>
	 <div class='am-form-group'>
       <label for='doc-select-1'>可用时间</label>
    <input id='date' name='date' type='text' value="<?php echo $row['time'];?>" class='am-form-field'>
    </div>
	<hr>
<button type='submit' class='am-btn am-btn-success' >更新</button>
  </fieldset>
		  </form>
		  </div>
		  <?php }
		  }
          ?>
		  </div>
        </div>
      </div>

      <div class="am-g">
        <div class="am-u-sm-12">
          <form class="am-form">
            <table class="am-table am-table-striped am-table-hover table-main">
              <thead>
              <tr>
                <th class="table-id am-hide-sm-only">ID</th><th class="table-title">名称</th><th class="table-type">拥有人</th><th class="table-author am-hide-sm-only">状态</th><th class="table-date ">可用时间</th><th class="table-set">操作</th>
              </tr>
              </thead>
              <tbody>
			               <?php
$perNumber=5;
@$page=$_GET['page'];
$count=query("select count(*) from server");
$rs=mysqli_fetch_array($count); 
$totalNumber=$rs[0];
$totalPage=ceil($totalNumber/$perNumber);
if (!isset($page)) {
 $page=1;
}
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
if($_SESSION['sec']!=1){
$result=query("select * from server where user='{$_SESSION['username']}' limit $startCount,$perNumber"); //根据前面的计算出开始的记录和记录数
$row = mysqli_fetch_array( query("SELECT COUNT(*) FROM server where user='{$_SESSION['username']}'") );
    
}else{
	$result=query("select * from server  limit $startCount,$perNumber"); 
	$row = mysqli_fetch_array( query("SELECT COUNT(*) FROM server ") );
}
if($row[0]==0){
	?>
	 <tr>
                <td>0</td>
                <td></td>
                <td></td>
                <td class="">暂无服务器可管理</td>
                <td class=""></td>
                <td>
                </td>
              </tr>
	<?php
}else{
while($row = mysqli_fetch_array($result))
{
	 $sname=$row['name']; 
	?>
              <tr <?php if($row['time']<=0){echo "class='am-danger'";}?>>
                <td class="am-hide-sm-only"><?php echo $row['id'];?></td>
<td><?php
if($row['time']>0){ 
?>
<a href="#" data-am-modal="{target:'#link<?php echo $row['sid']; ?>'}" >
<?php echo $sname;?>
</a>
<?php
}else{echo $sname;}
?>
</td>
                <td><?php echo $row['user'];?></td>
                <td class="am-hide-sm-only"><?php if($row['state']){echo "<a class='am-badge am-badge-success am-radius'>Online</a>";}else{echo "<a class='am-badge am-badge-danger am-radius'>Offline</a>";}?></td>
                <td class=""><?php echo $row['time']."天";?></td>
                <td>
                  <div class="am-btn-toolbar">
                    <div class="am-btn-group am-btn-group-xs">
					<?php 
					if($row['time']>0){	
						?>
<button type="button"class="am-btn am-btn-default am-btn-xs am-text-secondary" <?php if($row['time']>0){ echo 'onclick="javascript:window.location.href=\'manage.php?index&ser='.$row['sid'].'\'"';}?>><span class="am-icon-pencil-square-o"></span>产品管理</button> 
<?php } ?>
                      <button type="button"class="am-btn am-btn-default am-btn-xs am-text-success" onclick="javascript:window.location.href='list.php?renew=<?php echo $row['sid'];?>'" ><span class="am-icon-credit-card"></span>续费</button>
					 <button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"type="button" onclick="javascript:window.location.href='list.php?dels&&id=<?php echo $row['sid'];?>'"><span class="am-icon-trash-o"></span>删除</button>		
					</div>
				  </div>
                </td>
					  <div class="am-modal am-modal-alert" tabindex="-1" id="link<?php echo $row['sid'];?>">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">连接服务器</div>
    <div class="am-modal-bd">
     <div class="am-g">
  <div class="am-u-sm-6"><a href="steam://connect/<?php echo IP.":".$row['port'] + 1;?>">连接服务器</a></div><hr>
</div>
    </div>
  </div>
</div>
              </tr>
<?php }	
} ?>
              </tbody>
            </table>
            <div class="am-cf">
              共<?php echo $totalNumber; ?>条记录
              <div class="am-fr">
                <ul class="am-pagination">
					<?php
if ($page != 1) { 
?>
                  <li><a href="list.php?page=<?php echo $page-1;?>">«</a></li>
<?php }else{?>
 <li class="am-disabled"><a href="list.php?page=<?php echo $page-1;?>">«</a></li>
<?php }?>			  
				  <?php
if ($page<$totalPage) {
?>
                  <li><a href="list.php?page=<?php echo $page+1;?>">»</a></li>
<?php }else{?>
<li class="am-disabled"><a href="list.php?page=<?php echo $page+1;?>">»</a></li>
<?php }?>
                </ul>
              </div>
            </div>
            <hr />
            <p>注：.....</p>
          </form>
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
<script src="assets/js/jquery.min.js?v=1.0"></script>
<script src="assets/js/amazeui.min.js?v=1.0"></script>
<script src="assets/js/app.js?v=1.0"></script>
</body>
</html>
