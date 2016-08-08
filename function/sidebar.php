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
	    <li><a href='paw.php'><span class='am-icon-sign-out'></span> 修改密码</a></li>
        <li><a href='out.php'><span class='am-icon-sign-out'></span> 注销</a></li>
      </ul>
      <div class='am-panel am-panel-default admin-sidebar-panel'>
        <div class='am-panel-bd'>
          <p><span class='am-icon-tag'></span> wiki</p>
          <p>我才是帅比—— 7gugu</p>
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
        <li><a href='list.php'><span class='am-icon-home'></span>返回列表</a></li>
        <li><a href='manage.php?index'><span class='am-icon-check'></span>实时状态</a></li>
	    <li><a href='manage.php?information'><span class='am-icon-table'></span>信息设置</a></li>
        <li><a href='manage.php?order'><span class='am-icon-code'></span>指令面板</a></li>
		 <li><a href='manage.php?plugin'><span class='am-icon-plug'></span>插件管理</a></li>
		  <li><a href='manage.php?map'><span class='am-icon-map-o'></span>上传地图</a></li>
      </ul>  
	   <div class='am-panel am-panel-default admin-sidebar-panel'>
        <div class='am-panel-bd'>
          <p><span class='am-icon-bookmark'></span> 公告</p>
          <p>我才是帅比—— 7gugu</p>
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
	  <li><a href='list.php'><span class='am-icon-sign-out'></span>返回列表</a></li>
        <li><a href='admin_panel.php'><span class='am-icon-home'></span>管理首页</a></li>
        <li><a href='admin_panel.php?inser'><span class='am-icon-table'></span>管理激活码</a></li>
	    <li><a href='admin_panel.php?muser'><span class='am-icon-sign-out'></span>管理用户</a></li>
        <li><a href='admin_panel.php?mode'><span class='am-icon-sign-out'></span>管理模块</a></li>
		 <li><a href='admin_panel.php?cron'><span class='am-icon-sign-out'></span>计划任务</a></li>
		  <li><a href='admin_panel.php?notice'><span class='am-icon-sign-out'></span>发布公告</a></li>
      </ul>  
	   <div class='am-panel am-panel-default admin-sidebar-panel'>
        <div class='am-panel-bd'>
          <p><span class='am-icon-bookmark'></span> 公告</p>
          <p>我才是帅比—— 7gugu</p>
        </div>
      </div>
    </div>
  </div>
		";
	}
}else{
	echo "[2001]Sibebar wrong!";
	exit();
}

?> 
