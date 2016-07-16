<?php 
require 'function/corestart.php';
checkuser();
//接口sid
	if(isset($_GET['ser'])){
    $sid=$_GET['ser'];
	}else{
		echo "<script>alert('参数错误!')</script>";
sleep(2);
header("Location:list.php");
	}
	if(isset($_GET['save'])&&isset($_POST['text'])){
		$text=$_POST['text'];
		$text = trim($text); 
		if(isset($_GET['cdat'])){
			$text=htmlspecialchars($text);
		echo rwfile($sid,$text,'w','server\\Commands.dat');
		}elseif(isset($_GET['rxml'])){
			echo rwfile($sid,$text,'w','Rocket\\Rocket.config.xml');
		}
		exit();
	}
	if(isset($_GET['open'])&&isset($_GET['id'])){
		$ids=$_GET['id'];
		manage($ids,'start');
	}
		if(isset($_GET['end'])&&isset($_GET['id'])){
		$ids=$_GET['id'];
		manage($ids,'end');
	}
	if(isset($_POST['cdat'])){
		rwfile($sid,$_POST['cdat'],'w','server\\Commands.dat');
	}
	if(isset($_FILES['upfile'])){
		$ser=$_POST['SER'];
		$rem=upmap($_FILES['upfile']);
		$rez=getzip("upload/".$_FILES['upfile']['name'],"map/");
		if($rez==true&&$rem==true){	
		$id = query("select * from map order by id DESC limit 1 ");
	$id=mysql_fetch_array($id);
	if($id){
		$id=$id['id'];
	}else{
	$id=0;
	}
	$id++;
	$tname=explode(".",$_FILES['upfile']['name']);
		query("insert into map(id,name,state)values('$id','{$tname[0]}','1')");	
		header("Location:manage.php?ser={$ser}&&uok");//upload successfully
		}
		header("Location:manage.php?ser={$ser}&&uno");//upload faild
	}
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Ucon | manage</title>
  <meta name="description" content="这是一个 index 页面">
  <meta name="keywords" content="index">
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
  <script src="assets/js/save.js" text="text/javascript"></script>
  <script src="assets/ace/ace.js" type="text/javascript" charset="utf-8"></script>
  <style type="text/css" media="screen">
  pre{
	  padding:50%;
  }
    #editor {
       
        position: absolute;
      
    }
  </style>
</header>

<div class="am-cf admin-main">
  <!-- sidebar start -->
  <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
      <ul class="am-list admin-sidebar-list">
        <li><a href="admin-index.html"><span class="am-icon-home"></span> 首页</a></li>
        <li><a href="admin-table.html"><span class="am-icon-table"></span> 产品管理</a></li>
        <li><a href="admin-form.html"><span class="am-icon-pencil-square-o"></span> 账户明细</a></li>
        <li><a href="#"><span class="am-icon-sign-out"></span> 注销</a></li>
      </ul>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-bookmark"></span> 公告</p>
          <p>不氪金不是人。—— 7gugu</p>
        </div>
      </div>

     
    </div>
  </div>
  <!-- sidebar end -->

  <!-- content start -->
  <?php 
  $rs=query("select * from server where sid='$sid'");
  $rom=mysql_fetch_array($rs);
  $sname=iconv("GB2312","UTF-8//IGNORE",$rom['name']) 
  ?>
  <div class="admin-content">
  <div class="admin-content-body">
    <div class="am-cf am-padding am-padding-bottom-0">
      <div class="am-fl am-cf">
        <strong class="am-text-primary am-text-lg">管理服务器</strong> /
        <small><?php echo $sname;?></small>
      </div>
    </div>

    <hr>

    <div class="am-tabs am-margin" data-am-tabs>
      <ul class="am-tabs-nav am-nav am-nav-tabs">
        <li class="am-active"><a href="#tab1">基本信息</a></li>
        <li><a href="#tab2">文件管理</a></li>
        <li><a href="#tab3">上传地图</a></li>
      </ul>

      <div class="am-tabs-bd">
        <div class="am-tab-panel am-fade am-in am-active " id="tab1">
		 <div class="am-u-sm-7 ">
 
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">IP地址:</div>
        <div class="am-u-sm-8 am-u-md-10">
              <p><?php echo IP;?></p>
              </div>
          </div>
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">服务器名</div>
  <div class="am-u-sm-8 am-u-md-10">
              <p><?php echo $sname;?></p>
              </div>
          </div>
		   <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">端口</div>
  <div class="am-u-sm-8 am-u-md-10">
              <p>Game:<?php echo $rom['port'];?>/Rcon:<?php echo $rom['rport'];?></p>
              </div>
          </div>
		   <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">状态</div>
  <div class="am-u-sm-8 am-u-md-10">
              <p><?php
			  if($rom['state']==0){
				  echo "离线";
			  }elseif($rom['state']==1){
				  echo "在线";
			  }else{
				  echo "未知状态";
			  }
				  ?></p>
              </div>
          </div>
</div>
 <div class="am-u-sm-5">
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">启动服务</div>
            <div class="am-u-sm-8 am-u-md-10">
			<?php 
				$i=$rom['id'];
			if($rom['state']==0){
			
				echo " 
				<form action=\"manage.php?ser=1&open&id=$i\" method=\"POST\">
				<button type=\"submit\" class=\"am-btn am-btn-success\">启动服务器</button> 
				</form>
				";
			}elseif($rom['state']==1){
				echo " 
				<form action=\"manage.php?ser=1&end&id=$i\" method=\"POST\">
				<button type=\"submit\" class=\"am-btn am-btn-danger\">关闭服务器</button>
                </form>
				";
			}
			?>
            </div>
			
          </div>

          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
              剩余时间
            </div>
            <div class="am-u-sm-8 am-u-md-10">
             <p><?php echo $rom['time'];?>天</p>
            </div>
          </div>
</div>
        </div>
        <div class="am-tab-panel am-fade" id="tab2">  
		<div class="am-g am-g-fixed">
 <div class="am-u-sm-6 ">	
   <label for="doc-select-1">Command.dat</label>    
            <pre id="editorc">	
		<?php 
		$data =rwfile($sid,'','r','server\\Commands.dat');
echo htmlspecialchars($data);
	 ?>	 
</pre>
<script>
    var editorc = ace.edit("editorc");
    editorc.setTheme("assets/ace/theme/twilight");
    editorc.session.setMode("ace/mode/text");
</script>  
         <div class="am-u-sm-8 " style="position:fixed; bottom:15px;  width:100%; _position:absolute;">
            <button type="button" onclick="savec('<?php echo $sid;?>')" class="am-btn am-btn-success">保存更改</button>
            </div>
		  </div>  
		 <div class="am-u-sm-6 ">	
		   <label for="doc-select-1">Rocket.xml</label>
          <form id="2"class="am-form">  
            <pre id="editorr">	
		<?php 
		$data =$data =rwfile($sid,'','r','Rocket\\Rocket.config.xml');
echo htmlspecialchars($data);
	 ?> 
</pre>
<script>
    var editorr = ace.edit("editorr");
    editorr.setTheme("assets/ace/theme/twilight");
    editorr.session.setMode("ace/mode/xml");
</script>
  <div class="am-u-sm-8 am-u-md-10">
            <button type="button" onclick="saver()" class="am-btn am-btn-success">保存更改</button>
            </div>
          </form> 
		  </div>
		  </div>
        </div>
        <div class="am-tab-panel am-fade" id="tab3">
		<div class="am-form-group">
		<div class="am-u-sm-4 ">
	  <form class="am-form">
	  <?php
$result=query("select * from map where state ='1' "); 
?>

  <div class="am-u-md-8">
  <label for="doc-select-1">地图选择</label>
  <select multiple class="" id="doc-select-2">
  <?php
while($row = mysql_fetch_array($result))  
{
	?>
        <option><?php echo $row['name'];?></option>
		<?php
}
	?>
      </select> 
	  <p class="am-form-help">修改地图请通过编辑器修改,若没有想要的可自己上传</p>
			</div>
			 
	</form>
	</div>
	</div>
          <form class="am-form" action="" enctype="multipart/form-data" method="post">
            <div class="am-form-group">
			<div class="am-u-sm-8 ">
      <label for="doc-ipt-file-1">上传地图</label>
      <input type="file" id="doc-ipt-file-1" name="upfile">
	  <input type="hidden" name="ser" value="<?php  echo $_GET['ser'];?>">
      <p class="am-form-help">请把地图文件压缩成ZIP格式,上传后将会自动解压并分享予服务器</p>
	   <button type="submit" class="am-btn am-btn-warning">上传</button>
	  </div>
    </div>
          </form>
		</div>
  </div>
    <footer class="admin-content-footer">
      <hr>
      <p class="am-padding-left">© 2016 Power By 7gugu.</p>
    </footer>
  </div>
  <div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">修改成功
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      文件修改成功
    </div>
  </div>
</div>
  <!-- content end -->

</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
