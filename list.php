<?php 
require 'function/corestart.php';
checkuser();
if(isset($_GET['dels'])&&isset($_GET['id'])){
	$dels=$_GET['dels'];
	$sid=$_GET['id'];
$ok=deldir(PATHS."//Servers//{$sid}");
if($ok){
	query("delete from server where id='$sid'");
	$row=mysql_affected_rows();
if($row>0)
{
	$rs=query("select * from user where username='{$username}'");
	        $rom=mysql_fetch_array($rs);
			$cou=$rom['scout']-1;
			query("update user set scout ='{$cou}'where username='{$username}'");
	header("Location: list.php?s=4");//4删除成功
}else{
//header("Location: list.php?f=4");
}
}else{
//header("Location: list.php?f=5");//5文件夹删除失败
}
}
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Ucon | manage</title>
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
      <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span class="am-badge am-badge-warning">5</span></a></li>
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
        <li><a href="admin-index.html"><span class="am-icon-home"></span> 首页</a></li>
        <li><a href="admin-table.html"><span class="am-icon-table"></span> 表格</a></li>
        <li><a href="admin-form.html"><span class="am-icon-pencil-square-o"></span> 表单</a></li>
        <li><a href="#"><span class="am-icon-sign-out"></span> 注销</a></li>
      </ul>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-bookmark"></span> 公告</p>
          <p>时光静好，与君语；细水流年，与君同。—— Amaze UI</p>
        </div>
      </div>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-tag"></span> wiki</p>
          <p>Welcome to the Amaze UI wiki!</p>
        </div>
      </div>
    </div>
  </div>
  <!-- sidebar end -->

  <!-- content start -->
   <div class="admin-content">
    <div class="admin-content-body">
      <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">产品列表</strong> / <small>购买后的服务器都会在此显示</small></div>
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
$count=mysql_query("select count(*) from server");
$rs=mysql_fetch_array($count); 
$totalNumber=$rs[0];
$totalPage=ceil($totalNumber/$perNumber);
if (!isset($page)) {
 $page=1;
}
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
if($_SESSION['sec']!=1){
$result=mysql_query("select * from server where user='{$_SESSION['username']}' limit $startCount,$perNumber"); //根据前面的计算出开始的记录和记录数
}else{
	$result=mysql_query("select * from server  limit $startCount,$perNumber"); 
}
?>
		<script language="javascript">
	
					function jump(i){
					window.location="manage.php?ser="+i;
					}
					</script>
<?php
while($row = mysql_fetch_array($result))
  
{
	 $sname=iconv("GB2312","UTF-8//IGNORE",$row['name']) 
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
                      <button type="button"class="am-btn am-btn-default am-btn-xs am-text-secondary" onclick="jump('<?php echo $row['sid'];?>')"><span class="am-icon-pencil-square-o"></span>产品管理</button>
                     <button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"type="button" onclick="javascript:window.location.href='list.php?dels=<?php echo $row['id'];?>&&id=<?php echo $row['id'];?>'"><span class="am-icon-trash-o"></span>删除</button>		
					</div>
                  
				  </div>
                </td>
              </tr>
<?php }	 ?>
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
