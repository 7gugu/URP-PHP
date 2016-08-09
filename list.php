<?php 
require 'function/corestart.php';
checkuser();
$v="n";
if(isset($_COOKIE['ser'])){
	setcookie('ser','',time()-999999);
}
if(isset($_GET['dels'])&&isset($_GET['id'])){
	$sid=$_GET['id'];
$ok=ddf(PATHS."//Servers//{$sid}//");
if($ok){
	query("delete from server where sid='{$sid}'");
	$row=mysqli_affected_rows();
if($row>0)
{
	header("Location: list.php?suc=9");//4删除成功
}else{
header("Location: list.php?err=11");
}
}else{
header("Location: list.php?err=12");//5文件夹删除失败
}
}
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>URP | 产品列表</title>
  <meta name="description" content="产品列表">
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
</div>
      <hr>

      <div class="am-g"> 
        <div class="am-u-sm-12 am-u-md-3">
          <div class="am-input-group am-input-group-sm">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button" onclick="javascript:window.location.href='create.php?c0'">创建新的服务器</button>
          </span>
          </div>
        </div>
      </div>

      <div class="am-g">
        <div class="am-u-sm-12">
          <form class="am-form">
            <table class="am-table am-table-striped am-table-hover table-main">
              <thead>
              <tr>
                <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">名称</th><th class="table-type">拥有人</th><th class="table-author am-hide-sm-only">端口</th><th class="table-date am-hide-sm-only">可用时间</th><th class="table-set">操作</th>
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
}else{
	$result=query("select * from server  limit $startCount,$perNumber"); 
}
$row = mysqli_fetch_array( query("SELECT COUNT(*) FROM server where user='{$_SESSION['username']}'") );
if($row[0]==0){
	?>
	 <tr>
                <td><input type="checkbox" /></td>
                <td>0</td>
                <td></td>
                <td></td>
                <td class="am-hide-sm-only">暂无服务器可管理</td>
                <td class="am-hide-sm-only"></td>
                <td>
                </td>
              </tr>
	<?php
}else{
while($row = mysqli_fetch_array($result))
  
{
	 $sname=$row['name']; 
	?>
              <tr>
                <td><input type="checkbox" /></td>
                <td><?php echo $row['id'];?></td>
                <td><a href="#"><?php echo $sname;?></a></td>
                <td><?php echo $row['user'];?></td>
                <td class="am-hide-sm-only"><?php echo $row['port'];?></td>
                <td class="am-hide-sm-only"><?php echo $row['time']."天";?></td>
                <td>
                  <div class="am-btn-toolbar">
				  
                    <div class="am-btn-group am-btn-group-xs">
                      <button type="button"class="am-btn am-btn-default am-btn-xs am-text-secondary" onclick="javascript:window.location.href='manage.php?index&ser=<?php echo $row['sid'];?>'" <?php if($row['time']<=0){echo "disabled";}?>><span class="am-icon-pencil-square-o"></span>产品管理</button>
                     <button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"type="button" onclick="javascript:window.location.href='list.php?dels&&id=<?php echo $row['sid'];?>'"><span class="am-icon-trash-o"></span>删除</button>		
					</div>
                  
				  </div>
                </td>
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
