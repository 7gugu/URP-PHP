<?php 
if(isset($v)){
	if($v=="n"){
echo "
 <div class='admin-sidebar am-offcanvas' id='admin-offcanvas'>
    <div class='am-offcanvas-bar admin-offcanvas-bar'>
      <ul class='am-list admin-sidebar-list'>
        <li><a href='index.php'><span class='am-icon-home'></span> 首页</a></li>
        <li><a href='list.php'><span class='am-icon-table'></span> 产品列表</a></li>";
		if($_SESSION['sec']==1){	
    echo "<li><a href='admin_panel.php'><span class='am-icon-pencil-square-o'></span> 后台管理</a></li>";
		}
       echo "
	    <li><a href='paw.php'><span class='am-icon-undo'></span> 修改密码</a></li>
        <li><a href='out.php'><span class='am-icon-sign-out'></span> 注销</a></li>
      </ul>
      <div class='am-panel am-panel-default admin-sidebar-panel'>
        <div class='am-panel-bd'>
          <p><span class='am-icon-bookmark'></span>公告</p>
          <p>";
		   $notice=mysqli_fetch_array(query("select * from notice order by rand() limit 1"));
		   if($notice!=""){
				echo $notice['text'];
				 }else{
					 echo "服务器运作正常";
				 }
		  echo "---Admin</p>
        </div>
      </div>
    </div>
  </div>

";
	}
	if($v=="m"){
		echo "
 <div class='admin-sidebar am-offcanvas' id='admin-offcanvas'>
    <div class='am-offcanvas-bar admin-offcanvas-bar'>
      <ul class='am-list admin-sidebar-list'>
        <li><a href='list.php'><span class='am-icon-home'></span> 返回列表</a></li>
        <li><a href='manage.php?index'><span class='am-icon-check'></span> 实时状态</a></li>
	    <li><a href='manage.php?information'><span class='am-icon-table'></span> 信息设置</a></li>
        <li><a href='manage.php?order'><span class='am-icon-terminal'></span> 指令面板</a></li>
		 <li><a href='manage.php?plugin'><span class='am-icon-plug'></span> 插件管理</a></li>
		  <li><a href='manage.php?map'><span class='am-icon-map-o'></span> 上传地图</a></li>
		  <li><a href='manage.php?mod'><span class='am-icon-wrench'></span> 上传MOD</a></li>
		  <li><a href='manage.php?players'><span class='am-icon-user'></span> 在线玩家</a></li>
		  ";
	if(CSQL){	  
		 echo "<li><a href='manage.php?msql'><span class='am-icon-skyatlas'></span> 数据库管理</a></li>";
	}
		  echo "
      </ul>  
	   <div class='am-panel am-panel-default admin-sidebar-panel'>
        <div class='am-panel-bd'>
          <p><span class='am-icon-bookmark'></span> 公告</p>
          <p>";
		   $notice=mysqli_fetch_array(query("select * from notice order by rand() limit 1"));
		    if($notice!=""){
				echo $notice['text'];
				 }else{
					 echo "服务器运作正常";
				 }
		  echo "---Admin</p>
        </div>
      </div>
    </div>
  </div>
		";
	}
	if($v=="a"){
		echo "
 <div class='admin-sidebar am-offcanvas' id='admin-offcanvas'>
    <div class='am-offcanvas-bar admin-offcanvas-bar'>
      <ul class='am-list admin-sidebar-list'>
	  <li><a href='list.php'><span class='am-icon-sign-out'></span> 返回列表</a></li>
        <li><a href='admin_panel.php'><span class='am-icon-home'></span> 管理首页</a></li>
        <li><a href='admin_panel.php?inser'><span class='am-icon-exchange'></span> 管理激活码</a></li>
	    <li><a href='admin_panel.php?muser'><span class='am-icon-users'></span> 管理用户</a></li>
		 <li><a href='admin_panel.php?cron'><span class='am-icon-tag'></span> 计划任务</a></li>
		  <li><a href='admin_panel.php?notice'><span class='am-icon-arrows'></span> 发布公告</a></li>
		   <li><a href='admin_panel.php?mplugin'><span class='am-icon-upload'></span> 插件管理</a></li>
		   <li><a href='admin_panel.php?upgrade'><span class='am-icon-refresh'></span> 升级管理</a></li>
      </ul>  
	   <div class='am-panel am-panel-default admin-sidebar-panel'>
        <div class='am-panel-bd'>
          <p><span class='am-icon-bookmark'></span> 公告</p>
          <p>";
		   $notice=mysqli_fetch_array(query("select * from notice order by rand() limit 1"));
		    if($notice!=""){
				echo $notice['text'];
				 }else{
					 echo "服务器运作正常";
				 }
		  echo "---Admin</p>
        </div>
      </div>
    </div>
  </div>
		";
	}
}else{
	echo "[2001]Sidebar initialization failed!";
	exit();
}

?> 
